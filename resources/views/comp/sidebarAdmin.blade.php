<div class="sidebar">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">
                <li class = "@if($linkActive == 'admin') {{"active"}} @endif">
                    <a href="/admin">Dashboards
                    </a>
                </li>
                <li class = "@if($linkActive == 'order') {{"active"}} @endif">
                    <a href="/admin/order">
                        Order
                    </a>
                </li>
                <li class = "@if($linkActive == 'transaksi') {{"active"}} @endif">
                    <a href="/admin/transaksi">
                       Transaksi
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>