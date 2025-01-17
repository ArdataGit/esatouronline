<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <img src="{{ asset('assets/img/esa_logo.png') }}" alt="">
            {{-- <span class="app-brand-text demo menu-text fw-bold">ESA TOUR</span> --}}
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item @yield('home')">
            <a href="{{ route('backend.dashboard.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Home">Home</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Transactions">Transactions</span>
        </li>

        <!-- Invoice -->
        <li class="menu-item @yield('invoice')">
            <a href="{{ route('backend.invoice.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-invoice">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <path d="M9 7l1 0" />
                    <path d="M9 13l6 0" />
                    <path d="M13 17l2 0" />
                </svg>
                <div data-i18n="Invoice" style="margin-left:3%;">Invoice</div>
            </a>
        </li>

        <li class="menu-item @yield('credit_debit')">
            <a href="{{ route('backend.credit_debit.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-files">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                    <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" /></svg>
                <div data-i18n="Credit & Debit Note" style="margin-left:3%;">Credit & Debit Note</div>
            </a>
        </li>

        <li class="menu-item @yield('asset')">
            <a href="{{ route('backend.asset.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-cash">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 9m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                    <path d="M14 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 9v-2a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v6a2 2 0 0 0 2 2h2" /></svg>
                <div data-i18n="Modal" style="margin-left:3%;">Modal</div>
            </a>
        </li>

        <li class="menu-item @yield('expense')">
            <a href="{{ route('backend.expense.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-license">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-11a3 3 0 0 0 -3 3v11" />
                    <path d="M9 7l4 0" />
                    <path d="M9 11l4 0" /></svg>
                <div data-i18n="Pengeluaran" style="margin-left:3%;">Pengeluaran</div>
            </a>
        </li>

        <li class="menu-item @yield('deposit')">
            <a href="{{ route('backend.deposit.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-wallet">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
                    <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                <div data-i18n="Deposit" style="margin-left:3%;">Deposit</div>
            </a>
        </li>

        <li class="menu-item @yield('tax')">
            <a href="{{ route('backend.tax.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-tag-starred">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path
                        d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592 -5.592a2.41 2.41 0 0 0 0 -3.408l-7.71 -7.71a2 2 0 0 0 -1.414 -.586h-5.172a3 3 0 0 0 -3 3z" />
                    <path
                        d="M12.5 13.847l-1.5 1.153l.532 -1.857l-1.532 -1.143h1.902l.598 -1.8l.598 1.8h1.902l-1.532 1.143l.532 1.857z" />
                </svg>
                <div data-i18n="Pajak" style="margin-left:3%;">Pajak</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Laporan">Laporan</span>
        </li>

        <li class="menu-item @yield('scale')">
            <a href="{{ route('backend.scale.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-scale">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 20l10 0" />
                    <path d="M6 6l6 -1l6 1" />
                    <path d="M12 3l0 17" />
                    <path d="M9 12l-3 -6l-3 6a3 3 0 0 0 6 0" />
                    <path d="M21 12l-3 -6l-3 6a3 3 0 0 0 6 0" /></svg>
                <div data-i18n="Neraca" style="margin-left:3%;">Neraca</div>
            </a>
        </li>

        <li class="menu-item @yield('sale')">
            <a href="{{ route('backend.sale.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-rosette-discount">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 15l6 -6" />
                    <circle cx="9.5" cy="9.5" r=".5" fill="currentColor" />
                    <circle cx="14.5" cy="14.5" r=".5" fill="currentColor" />
                    <path
                        d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7a2.2 2.2 0 0 0 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1a2.2 2.2 0 0 0 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" />
                </svg>
                <div data-i18n="Penjualan" style="margin-left:3%;">Penjualan</div>
            </a>
        </li>

        <li class="menu-item @yield('profit_loss')">
            <a href="{{ route('backend.profit_loss.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-cash-register">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M21 15h-2.5c-.398 0 -.779 .158 -1.061 .439c-.281 .281 -.439 .663 -.439 1.061c0 .398 .158 .779 .439 1.061c.281 .281 .663 .439 1.061 .439h1c.398 0 .779 .158 1.061 .439c.281 .281 .439 .663 .439 1.061c0 .398 -.158 .779 -.439 1.061c-.281 .281 -.663 .439 -1.061 .439h-2.5" />
                    <path d="M19 21v1m0 -8v1" />
                    <path
                        d="M13 21h-7c-.53 0 -1.039 -.211 -1.414 -.586c-.375 -.375 -.586 -.884 -.586 -1.414v-10c0 -.53 .211 -1.039 .586 -1.414c.375 -.375 .884 -.586 1.414 -.586h2m12 3.12v-1.12c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-2" />
                    <path
                        d="M16 10v-6c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-4c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414v6m8 0h-8m8 0h1m-9 0h-1" />
                    <path d="M8 14v.01" />
                    <path d="M8 17v.01" />
                    <path d="M12 13.99v.01" />
                    <path d="M12 17v.01" /></svg>
                <div data-i18n="Laba Rugi" style="margin-left:3%;">Laba Rugi</div>
            </a>
        </li>


        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Master Data">Master Data</span>
        </li>

        <li class="menu-item @yield('product')">
            <a href="{{ route('backend.product.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l14 1l-1 7h-13" /></svg>
                <div data-i18n="Produk" style="margin-left:3%;">Produk</div>
            </a>
        </li>

        <li class="menu-item @yield('customer')">
            <a href="{{ route('backend.customer.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Pelanggan">Pelanggan</div>
            </a>
        </li>

        <li class="menu-item @yield('bank')">
            <a href="{{ route('backend.bank.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-building-bank">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 21l18 0" />
                    <path d="M3 10l18 0" />
                    <path d="M5 6l7 -3l7 3" />
                    <path d="M4 10l0 11" />
                    <path d="M20 10l0 11" />
                    <path d="M8 14l0 3" />
                    <path d="M12 14l0 3" />
                    <path d="M16 14l0 3" /></svg>
                <div data-i18n="Bank" style="margin-left:3%;">Bank</div>
            </a>
        </li>

        <li class="menu-item @yield('category_note')" style="margin-bottom: 20%;">
            <a href="{{ route('backend.category_note.index') }}" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-category">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 4h6v6h-6z" />
                    <path d="M14 4h6v6h-6z" />
                    <path d="M4 14h6v6h-6z" />
                    <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                <div data-i18n="Kategori Note" style="margin-left:3%;">Kategori Note</div>
            </a>
        </li>

        {{-- <li class="menu-item @yield('physical_invoice')">
            <a href="{{ route('backend.physical_invoice.index') }}" class="menu-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-devices">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M13 9a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1v-10z" />
            <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9" />
            <path d="M16 9h2" /></svg>
        <div data-i18n="Invoice Fisik" style="margin-left:3%;">Invoice Fisik</div>
        </a>
        </li> --}}
    </ul>
</aside>
