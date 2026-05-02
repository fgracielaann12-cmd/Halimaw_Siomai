<?php
namespace App\Services;
use App\Models\ItemModel;

class DashboardService
{
    protected $itemModel;
    public function __construct()
    {
        $this->itemModel = new ItemModel();
    }

    public function getExpiringItems($days = 30)
    {
        $today = date('Y-m-d');
        $until = date('Y-m-d', strtotime("+$days days"));
        $builder = $this->itemModel->builder();
        $builder->where('expiration_date IS NOT NULL', null, false);
        $builder->where('expiration_date >=', $today);
        $builder->where('expiration_date <=', $until);
        $builder->orderBy('expiration_date', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function expiringChartData($days = 30)
    {
        $items = $this->getExpiringItems($days);
        $buckets = [];
        foreach ($items as $it) {
            $diff = (strtotime($it['expiration_date']) - strtotime(date('Y-m-d'))) / 86400;
            $bucket = floor($diff);
            $bucket = max(0, $bucket);
            $buckets[$bucket] = ($buckets[$bucket] ?? 0) + 1;
        }
        ksort($buckets);
        $labels = array_map(function($d){ return $d . 'd'; }, array_keys($buckets));
        $data = array_values($buckets);
        return ['labels' => $labels, 'data' => $data];
    }
}
