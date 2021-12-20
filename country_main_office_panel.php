<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15.02.2019
 * Time: 02:14
 */
include 'vendor/autoload.php';
$dotenv =\Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


include_once "public_functions.php";

getHeader();
getNavbar();
?>

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h4>Admin Panel</h4>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Manage Employees</a>
                </li>
                <li class="nav-item" style="padding-left: 10px;">
                    <a class="nav-link active" href="#">Manage Customers</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container">

        <div class="card bg-light text-dark">
            <div class="card-header">
                Operations
                <button type="button" class="btn btn-outline-info float-right"><i class="fas fa-plus-circle"></i> Start New Operation</button>
            </div>
            <div class="card-body" style="padding-top: 0;">
                <!-- Tab panes -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#waiting">Waiting List <span class="badge badge-danger">4</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#active">Active List <span class="badge badge-danger">4</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#completed">Completed List <span class="badge badge-danger">4</span></a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div id="waiting" class="container tab-pane active"><br>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Operation ID</th>
                                <th>Location</th>
                                <th>Customer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>234</td>
                                <td>Russia</td>
                                <td>Customer 1</td>
                            </tr>
                            <tr>
                                <td>123</td>
                                <td>Ukrania</td>
                                <td>Customer 2</td>
                            </tr>
                            <tr>
                                <td>654</td>
                                <td>Turkey</td>
                                <td>Customer 3</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="active" class="container tab-pane fade"><br>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Operation ID</th>
                                <th>Location</th>
                                <th>Customer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>234</td>
                                <td>Russia</td>
                                <td>Customer 1</td>
                            </tr>
                            <tr>
                                <td>123</td>
                                <td>Ukrania</td>
                                <td>Customer 2</td>
                            </tr>
                            <tr>
                                <td>654</td>
                                <td>Turkey</td>
                                <td>Customer 3</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="completed" class="container tab-pane fade"><br>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Operation ID</th>
                                <th>Location</th>
                                <th>Customer</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>234</td>
                                <td>Russia</td>
                                <td>Customer 1</td>
                            </tr>
                            <tr>
                                <td>123</td>
                                <td>Ukrania</td>
                                <td>Customer 2</td>
                            </tr>
                            <tr>
                                <td>654</td>
                                <td>Turkey</td>
                                <td>Customer 3</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php
getFooter();
?>