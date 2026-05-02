<?php

namespace App\Controllers;

use App\Models\SalesModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Models\TransactionModel;
use CodeIgniter\Controller;

class SalesController extends BaseController
{
    protected $salesModel;
    protected $productModel;
    protected $userModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->salesModel       = new SalesModel();
        $this->productModel     = new ProductModel();
        $this->userModel        = new UserModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        // Mark sales as seen to clear the notification badge
        $this->salesModel->where('is_seen', 0)->set(['is_seen' => 1])->update();

        $sales = $this->salesModel
            ->select('sales.*, products.name as product_name, users.username as user_name')
            ->join('products', 'products.id = sales.product_id', 'left')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->orderBy('sales.created_at', 'DESC')
            ->findAll();

        foreach ($sales as &$sale) {
            if (empty($sale->product_name)) {
                $product = $this->productModel->find($sale->product_id);
                $sale->product_name = $product ? $product['name'] : 'Unknown Product (ID: ' . esc($sale->product_id) . ')';
            }
            if (empty($sale->user_name)) {
                $user = $this->userModel->find($sale->user_id);
                $sale->user_name = $user ? $user['username'] : 'Unknown User (ID: ' . esc($sale->user_id) . ')';
            }
            if (empty($sale->pack)) {
                $sale->pack = '1pc';
            }
        }

        // 📈 DASHBOARD METRICS RESTORED
        $calcGrowth = function($current, $previous) {
            if ($previous == 0) return $current > 0 ? 100 : 0;
            return (($current - $previous) / $previous) * 100;
        };

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $dailySalesQuery = $this->salesModel->db->query("
            SELECT SUM(CASE WHEN DATE(created_at) = '$today' THEN total ELSE 0 END) as sales_today,
                   SUM(CASE WHEN DATE(created_at) = '$yesterday' THEN total ELSE 0 END) as sales_yesterday
            FROM sales WHERE created_at >= '$yesterday'
        ")->getRow();
        $salesToday = $dailySalesQuery->sales_today ?? 0;
        $salesYesterday = $dailySalesQuery->sales_yesterday ?? 0;
        $dailyGrowth = $calcGrowth($salesToday, $salesYesterday);

        $weeklySalesQuery = $this->salesModel->db->query("
            SELECT SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) THEN total ELSE 0 END) as sales_this_week,
                   SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK, 1) THEN total ELSE 0 END) as sales_last_week
            FROM sales WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 2 WEEK)
        ")->getRow();
        $salesThisWeek = $weeklySalesQuery->sales_this_week ?? 0;
        $salesLastWeek = $weeklySalesQuery->sales_last_week ?? 0;
        $weeklyGrowth = $calcGrowth($salesThisWeek, $salesLastWeek);

        $thisMonthStart = date('Y-m-01 00:00:00');
        $lastMonthStart = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $lastMonthEnd = date('Y-m-t 23:59:59', strtotime('last day of last month'));
        $monthlySalesQuery = $this->salesModel->db->query("
            SELECT SUM(CASE WHEN created_at >= '$thisMonthStart' THEN total ELSE 0 END) as sales_this_month,
                   SUM(CASE WHEN created_at >= '$lastMonthStart' AND created_at <= '$lastMonthEnd' THEN total ELSE 0 END) as sales_last_month,
                   COUNT(CASE WHEN created_at >= '$thisMonthStart' THEN id ELSE NULL END) as orders_this_month,
                   COUNT(CASE WHEN created_at >= '$lastMonthStart' AND created_at <= '$lastMonthEnd' THEN id ELSE NULL END) as orders_last_month
            FROM sales WHERE created_at >= '$lastMonthStart'
        ")->getRow();
        $salesThisMonth = $monthlySalesQuery->sales_this_month ?? 0;
        $salesLastMonth = $monthlySalesQuery->sales_last_month ?? 0;
        $monthlyGrowth = $calcGrowth($salesThisMonth, $salesLastMonth);
        $ordersThisMonth = $monthlySalesQuery->orders_this_month ?? 0;
        $ordersLastMonth = $monthlySalesQuery->orders_last_month ?? 0;
        $ordersGrowth = $calcGrowth($ordersThisMonth, $ordersLastMonth);

        $salesTrendQuery = $this->salesModel->db->query("
            SELECT DATE(created_at) as date, SUM(total) as daily_total FROM sales 
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(created_at) ORDER BY DATE(created_at) ASC
        ")->getResultArray();
        
        // Ensure today is always on the daily chart so it matches the card
        if (empty($salesTrendQuery) || end($salesTrendQuery)['date'] !== date('Y-m-d')) {
            $salesTrendQuery[] = ['date' => date('Y-m-d'), 'daily_total' => 0];
        }
        $salesTrendDataJSON = json_encode($salesTrendQuery);
        $dailyTrend = json_encode(array_column($salesTrendQuery, 'daily_total'));

        // Weekly Trends with labels
        $weeklyRaw = $this->salesModel->db->query("SELECT YEARWEEK(created_at, 1) as yw, CONCAT('Week of ', DATE_FORMAT(MIN(created_at), '%M %d')) as w_label, SUM(total) as w_total FROM sales WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 5 WEEK) GROUP BY yw ORDER BY yw ASC")->getResultArray();
        if (empty($weeklyRaw) || end($weeklyRaw)['yw'] != date('oW')) {
            $weeklyRaw[] = ['w_label' => 'This Week', 'w_total' => 0];
        }
        $weeklyTrend = json_encode(array_column($weeklyRaw, 'w_total'));
        $weeklyLabels = json_encode(array_column($weeklyRaw, 'w_label'));

        // Monthly Trends with labels
        $monthlyTrendRaw = $this->salesModel->db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as ym, DATE_FORMAT(created_at, '%b %Y') as m_label, SUM(total) as m_total, COUNT(id) as m_orders FROM sales WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH) GROUP BY ym ORDER BY ym ASC")->getResultArray();
        if (empty($monthlyTrendRaw) || end($monthlyTrendRaw)['ym'] !== date('Y-m')) {
            $monthlyTrendRaw[] = ['m_label' => date('M Y'), 'm_total' => 0, 'm_orders' => 0];
        }
        $monthlyTrend = json_encode(array_column($monthlyTrendRaw, 'm_total'));
        $monthlyLabels = json_encode(array_column($monthlyTrendRaw, 'm_label'));
        $ordersTrend = json_encode(array_column($monthlyTrendRaw, 'm_orders'));

        $metricsData = [
            'daily' => ['value' => $salesToday, 'growth' => $dailyGrowth, 'trend' => $dailyTrend],
            'weekly' => ['value' => $salesThisWeek, 'growth' => $weeklyGrowth, 'trend' => $weeklyTrend, 'labels' => $weeklyLabels],
            'monthly' => ['value' => $salesThisMonth, 'growth' => $monthlyGrowth, 'trend' => $monthlyTrend, 'labels' => $monthlyLabels],
            'orders' => ['value' => $ordersThisMonth, 'growth' => $ordersGrowth, 'trend' => $ordersTrend, 'labels' => $monthlyLabels]
        ];

        $topItemsQuery = $this->salesModel->db->query("
            SELECT product_id, SUM(quantity) as total_quantity, SUM(total) as total_value
            FROM sales 
            GROUP BY product_id
            ORDER BY total_value DESC
            LIMIT 5
        ")->getResultArray();

        foreach ($topItemsQuery as &$t) {
            $product = $this->productModel->find($t['product_id']);
            $t['name'] = $product ? $product['name'] : 'Unknown Product (ID: ' . $t['product_id'] . ')';
        }
        $topItemsDataJSON = json_encode($topItemsQuery);

        $data = [
            'sales'          => $sales,
            'salesTrendData' => $salesTrendDataJSON,
            'topItemsData'   => $topItemsDataJSON,
            'metricsData'    => $metricsData,
            'title'          => 'Sales Overview',
            'currentPath'    => uri_string(),
        ];

        return view('sales/list', $data);
    }

    public function transactions()
    {
        $transactions = $this->transactionModel
            ->select('transactions.*, users.username as user_name')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll();

        $data = [
            'transactions' => $transactions,
            'title'        => 'Transaction History',
            'currentPath'  => uri_string(),
        ];

        return view('sales/transactions', $data);
    }

    public function getTransactionItems($transactionId)
    {
        $items = $this->salesModel
            ->select('sales.*, items.name as product_name')
            ->join('items', 'items.id = sales.product_id', 'left')
            ->where('sales.transaction_id', $transactionId)
            ->findAll();

        return $this->response->setJSON(['success' => true, 'items' => $items]);
    }
}
