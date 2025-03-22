

<div class="sidebar-theme collapsed">
    <div class="side-brand">
        <img src="{{asset('assets/images/jc-logo.png')}}">
        <span>JC Ledgers</span>
    </div>
    <div class="menu-list-box">
        <ul class="menu-list">

            <a href="{{route('subadmin.dashboard')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                <span class="menu-text">Dashboard</span>
            </div></a>

            <!-- <div class="menu-list-item dropdown">
                <span class="menu-icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="menu-text">About</span>
                
                <div class="dropdown-menu-list">
                    <li>My List</li>
                    <li>My List</li>
                    <li>My List</li>
                </div>

            </div> -->

            <a href="{{route('subadmin.excel.logs')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-file-excel"></i></span>
                <span class="menu-text">Records</span>
            </div>    

            <a href="{{route('subadmin.mis.store')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-circle-info"></i></span>
                <span class="menu-text">MIS</span>
            </div></a>

            <a href="{{route('subadmin.bc-ledger.index')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-file-invoice"></i></span>
                <span class="menu-text">Ledger</span>
            </div></a>

            <a href="{{route('subadmin.csps')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-user-plus"></i></span>
                <span class="menu-text">CSP Agents</span>
            </div></a>

           


        </ul>
    </div>
</div>

<!-- Open Wrap  -->
<div class="page-wrapper-theme">
