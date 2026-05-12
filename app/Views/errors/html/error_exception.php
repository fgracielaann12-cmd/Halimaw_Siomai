<?php
use CodeIgniter\HTTP\Header;
use CodeIgniter\CodeIgniter;

$errorId = uniqid('error', true);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">

    <title><?= esc($title) ?></title>
    <style>
        <?= preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.css')) ?>
    </style>

    <script>
        <?= file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.js') ?>
    </script>
    
    
    
    
    
    
    
    
    
    <!-- UNIFIED 12PX SYSTEM-WIDE RADIUS OVERRIDE -->
    <style>
        :root {
            --border-radius: 12px !important;
        }
        
        /* Buttons */
        button, .btn, .btn-icon, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light, .btn-add-to-cart, .submit-button, a.btn, .chart-filter-btn, .btn-export, .btn-add-new-item,
        
        /* Textboxes / Inputs */
        input, select, textarea, .form-control, .form-select, .custom-input-group,
        
        /* Tables & Wrappers */
        .table, .table-card, .table-responsive, table, .dataTables_wrapper,
        
        /* Cards & Misc UI */
        .card, .pos-item-card, .summary-card, .img-metric-card, .chart-card-premium, .pos-checkout,
        .alert, .badge, .modal-content, .modal-header, .nav-link, .login-card,
        
        /* Bootstrap Overrides */
        .rounded, .rounded-1, .rounded-2, .rounded-3, .rounded-circle, .rounded-pill,
        .rounded-top, .rounded-bottom, .rounded-start, .rounded-end {
            border-radius: 12px !important;
        }
        
        /* Images inside cards */
        .pos-item-card img, .card img {
            border-radius: 12px !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        /* --- UNIFIED TABLE SCROLLING & SIZING FIX --- */
        .table, table {
            font-size: 0.95rem !important;
        }
        .table th, .table td, table th, table td {
            padding: 12px 15px !important;
            vertical-align: middle !important;
        }
        @media (max-width: 991px) {
            .table, table { font-size: 0.9rem !important; }
            .table th, .table td, table th, table td { padding: 0.75rem 0.5rem !important; }
        }
        .table-responsive, .table-responsive-custom {
            max-height: 65vh !important;
            overflow-y: auto !important;
        }
        .table-responsive::-webkit-scrollbar, .table-responsive-custom::-webkit-scrollbar {
            width: 8px; height: 8px;
        }
        .table-responsive::-webkit-scrollbar-track, .table-responsive-custom::-webkit-scrollbar-track {
            background: #f1f1f1; border-radius: 4px; margin: 0 10px;
        }
        .table-responsive::-webkit-scrollbar-thumb, .table-responsive-custom::-webkit-scrollbar-thumb {
            background: #c1c1c1; border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover, .table-responsive-custom::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        /* Sticky Headers */
        .table thead th, table thead th, .table th {
            position: sticky !important;
            top: -1px !important;
            z-index: 10 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            background-color: var(--primary, #4e73df) !important;
            color: white !important;
        }
        /* Fix dropdown clipping globally */
        .controls-section {
            position: relative;
            z-index: 10 !important;
        }
    </style>

    

    <!-- DISABLE BROWSER BACK/FORWARD BUTTONS COMPLETELY -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        // Push an empty state immediately
        history.pushState(null, null, location.href);
        // If the user tries to go back, instantly push them forward again
        window.onpopstate = function () {
            history.go(1);
        };
        
        function enforceClientAuth() {
            if (localStorage.getItem('auth_status') === 'logged_out') {
                document.documentElement.style.display = 'none';
                if(document.body) document.body.style.display = 'none';
                window.location.replace('/Halimaw_Siomai/index.php/login?blocked=1&cb=' + new Date().getTime());
            }
        }
        enforceClientAuth();
        window.addEventListener('pageshow', enforceClientAuth);
    </script>
</head>
<body onload="init()">

    <!-- Header -->
    <div class="header">
        <div class="environment">
            Displayed at <?= esc(date('H:i:s')) ?> &mdash;
            PHP: <?= esc(PHP_VERSION) ?>  &mdash;
            CodeIgniter: <?= esc(CodeIgniter::CI_VERSION) ?> --
            Environment: <?= ENVIRONMENT ?>
        </div>
        <div class="container">
            <h1><?= esc($title), esc($exception->getCode() ? ' #' . $exception->getCode() : '') ?></h1>
            <p>
                <?= nl2br(esc($exception->getMessage())) ?>
                <a href="https://www.duckduckgo.com/?q=<?= urlencode($title . ' ' . preg_replace('#\'.*\'|".*"#Us', '', $exception->getMessage())) ?>"
                   rel="noreferrer" target="_blank">search &rarr;</a>
            </p>
        </div>
    </div>

    <!-- Source -->
    <div class="container">
        <p><b><?= esc(clean_path($file)) ?></b> at line <b><?= esc($line) ?></b></p>

        <?php if (is_file($file)) : ?>
            <div class="source">
                <?= static::highlightFile($file, $line, 15); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="container">
        <?php
        $last = $exception;

        while ($prevException = $last->getPrevious()) {
            $last = $prevException;
            ?>

    <pre>
    Caused by:
    <?= esc($prevException::class), esc($prevException->getCode() ? ' #' . $prevException->getCode() : '') ?>

    <?= nl2br(esc($prevException->getMessage())) ?>
    <a href="https://www.duckduckgo.com/?q=<?= urlencode($prevException::class . ' ' . preg_replace('#\'.*\'|".*"#Us', '', $prevException->getMessage())) ?>"
       rel="noreferrer" target="_blank">search &rarr;</a>
    <?= esc(clean_path($prevException->getFile()) . ':' . $prevException->getLine()) ?>
    </pre>

        <?php
        }
        ?>
    </div>

    <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE) : ?>
    <div class="container">

        <ul class="tabs" id="tabs">
            <li><a href="#backtrace">Backtrace</a></li>
            <li><a href="#server">Server</a></li>
            <li><a href="#request">Request</a></li>
            <li><a href="#response">Response</a></li>
            <li><a href="#files">Files</a></li>
            <li><a href="#memory">Memory</a></li>
        </ul>

        <div class="tab-content">

            <!-- Backtrace -->
            <div class="content" id="backtrace">

                <ol class="trace">
                <?php foreach ($trace as $index => $row) : ?>

                    <li>
                        <p>
                            <!-- Trace info -->
                            <?php if (isset($row['file']) && is_file($row['file'])) : ?>
                                <?php
                                if (isset($row['function']) && in_array($row['function'], ['include', 'include_once', 'require', 'require_once'], true)) {
                                    echo esc($row['function'] . ' ' . clean_path($row['file']));
                                } else {
                                    echo esc(clean_path($row['file']) . ' : ' . $row['line']);
                                }
                                ?>
                            <?php else: ?>
                                {PHP internal code}
                            <?php endif; ?>

                            <!-- Class/Method -->
                            <?php if (isset($row['class'])) : ?>
                                &nbsp;&nbsp;&mdash;&nbsp;&nbsp;<?= esc($row['class'] . $row['type'] . $row['function']) ?>
                                <?php if (! empty($row['args'])) : ?>
                                    <?php $argsId = $errorId . 'args' . $index ?>
                                    ( <a href="#" onclick="return toggle('<?= esc($argsId, 'attr') ?>');">arguments</a> )
                                    <div class="args" id="<?= esc($argsId, 'attr') ?>">
                                        <table cellspacing="0">

                                        <?php
                                        $params = null;
                                        // Reflection by name is not available for closure function
                                        if (! str_ends_with($row['function'], '}')) {
                                            $mirror = isset($row['class']) ? new ReflectionMethod($row['class'], $row['function']) : new ReflectionFunction($row['function']);
                                            $params = $mirror->getParameters();
                                        }

                                        foreach ($row['args'] as $key => $value) : ?>
                                            <tr>
                                                <td><code><?= esc(isset($params[$key]) ? '$' . $params[$key]->name : "#{$key}") ?></code></td>
                                                <td><pre><?= esc(print_r($value, true)) ?></pre></td>
                                            </tr>
                                        <?php endforeach ?>

                                        </table>
                                    </div>
                                <?php else : ?>
                                    ()
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if (! isset($row['class']) && isset($row['function'])) : ?>
                                &nbsp;&nbsp;&mdash;&nbsp;&nbsp;    <?= esc($row['function']) ?>()
                            <?php endif; ?>
                        </p>

                        <!-- Source? -->
                        <?php if (isset($row['file']) && is_file($row['file']) && isset($row['class'])) : ?>
                            <div class="source">
                                <?= static::highlightFile($row['file'], $row['line']) ?>
                            </div>
                        <?php endif; ?>
                    </li>

                <?php endforeach; ?>
                </ol>

            </div>

            <!-- Server -->
            <div class="content" id="server">
                <?php foreach (['_SERVER', '_SESSION'] as $var) : ?>
                    <?php
                    if (empty($GLOBALS[$var]) || ! is_array($GLOBALS[$var])) {
                        continue;
                    } ?>

                    <h3>$<?= esc($var) ?></h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($GLOBALS[$var] as $key => $value) : ?>
                            <tr>
                                <td><?= esc($key) ?></td>
                                <td>
                                    <?php if (is_string($value)) : ?>
                                        <?= esc($value) ?>
                                    <?php else: ?>
                                        <pre><?= esc(print_r($value, true)) ?></pre>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endforeach ?>

                <!-- Constants -->
                <?php $constants = get_defined_constants(true); ?>
                <?php if (! empty($constants['user'])) : ?>
                    <h3>Constants</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($constants['user'] as $key => $value) : ?>
                            <tr>
                                <td><?= esc($key) ?></td>
                                <td>
                                    <?php if (is_string($value)) : ?>
                                        <?= esc($value) ?>
                                    <?php else: ?>
                                        <pre><?= esc(print_r($value, true)) ?></pre>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Request -->
            <div class="content" id="request">
                <?php $request = service('request'); ?>

                <table>
                    <tbody>
                        <tr>
                            <td style="width: 10em">Path</td>
                            <td><?= esc($request->getUri()) ?></td>
                        </tr>
                        <tr>
                            <td>HTTP Method</td>
                            <td><?= esc($request->getMethod()) ?></td>
                        </tr>
                        <tr>
                            <td>IP Address</td>
                            <td><?= esc($request->getIPAddress()) ?></td>
                        </tr>
                        <tr>
                            <td style="width: 10em">Is AJAX Request?</td>
                            <td><?= $request->isAJAX() ? 'yes' : 'no' ?></td>
                        </tr>
                        <tr>
                            <td>Is CLI Request?</td>
                            <td><?= $request->isCLI() ? 'yes' : 'no' ?></td>
                        </tr>
                        <tr>
                            <td>Is Secure Request?</td>
                            <td><?= $request->isSecure() ? 'yes' : 'no' ?></td>
                        </tr>
                        <tr>
                            <td>User Agent</td>
                            <td><?= esc($request->getUserAgent()->getAgentString()) ?></td>
                        </tr>

                    </tbody>
                </table>


                <?php $empty = true; ?>
                <?php foreach (['_GET', '_POST', '_COOKIE'] as $var) : ?>
                    <?php
                    if (empty($GLOBALS[$var]) || ! is_array($GLOBALS[$var])) {
                        continue;
                    } ?>

                    <?php $empty = false; ?>

                    <h3>$<?= esc($var) ?></h3>

                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($GLOBALS[$var] as $key => $value) : ?>
                            <tr>
                                <td><?= esc($key) ?></td>
                                <td>
                                    <?php if (is_string($value)) : ?>
                                        <?= esc($value) ?>
                                    <?php else: ?>
                                        <pre><?= esc(print_r($value, true)) ?></pre>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endforeach ?>

                <?php if ($empty) : ?>

                    <div class="alert">
                        No $_GET, $_POST, or $_COOKIE Information to show.
                    </div>

                <?php endif; ?>

                <?php $headers = $request->headers(); ?>
                <?php if (! empty($headers)) : ?>

                    <h3>Headers</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Header</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($headers as $name => $value) : ?>
                            <tr>
                                <td><?= esc($name, 'html') ?></td>
                                <td>
                                <?php
                                if ($value instanceof Header) {
                                    echo esc($value->getValueLine(), 'html');
                                } else {
                                    foreach ($value as $i => $header) {
                                        echo ' ('. $i+1 . ') ' . esc($header->getValueLine(), 'html');
                                    }
                                }
                                ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            </div>

            <!-- Response -->
            <?php
                $response = service('response');
                $response->setStatusCode(http_response_code());
            ?>
            <div class="content" id="response">
                <table>
                    <tr>
                        <td style="width: 15em">Response Status</td>
                        <td><?= esc($response->getStatusCode() . ' - ' . $response->getReasonPhrase()) ?></td>
                    </tr>
                </table>

                <?php $headers = $response->headers(); ?>
                <?php if (! empty($headers)) : ?>
                    <h3>Headers</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Header</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($headers as $name => $value) : ?>
                            <tr>
                                <td><?= esc($name, 'html') ?></td>
                                <td>
                                <?php
                                if ($value instanceof Header) {
                                    echo esc($response->getHeaderLine($name), 'html');
                                } else {
                                    foreach ($value as $i => $header) {
                                        echo ' ('. $i+1 . ') ' . esc($header->getValueLine(), 'html');
                                    }
                                }
                                ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            </div>

            <!-- Files -->
            <div class="content" id="files">
                <?php $files = get_included_files(); ?>

                <ol>
                <?php foreach ($files as $file) :?>
                    <li><?= esc(clean_path($file)) ?></li>
                <?php endforeach ?>
                </ol>
            </div>

            <!-- Memory -->
            <div class="content" id="memory">

                <table>
                    <tbody>
                        <tr>
                            <td>Memory Usage</td>
                            <td><?= esc(static::describeMemory(memory_get_usage(true))) ?></td>
                        </tr>
                        <tr>
                            <td style="width: 12em">Peak Memory Usage:</td>
                            <td><?= esc(static::describeMemory(memory_get_peak_usage(true))) ?></td>
                        </tr>
                        <tr>
                            <td>Memory Limit:</td>
                            <td><?= esc(ini_get('memory_limit')) ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>  <!-- /tab-content -->

    </div> <!-- /container -->
    <?php endif; ?>

</body>
</html>
