

<div class="sidebar-theme collapsed">
    <div class="side-brand">
        <img src="{{asset('assets/images/jc-logo.png')}}">
        <span>JC Ledgers</span>
    </div>
    <div class="menu-list-box">
        <ul class="menu-list">

            <a href="{{route('admin.dashboard')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                <span class="menu-text">Dashboard</span>
            </div></a>
<!-- 
            <div class="menu-list-item dropdown">
                <span class="menu-icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="menu-text">Users</span>
                
                <div class="dropdown-menu-list">
                    <li>CSP Agents</li>
                    <li>Subadmins</li>
                    <li>My List</li>
                </div>

            </div> -->

            <a href="{{route('admin.excel.logs')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-file-excel"></i></span>
                <span class="menu-text">Records</span>
            </div></a>

            <a href="{{route('bc-ledger.index')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-file-invoice"></i></span>
                <span class="menu-text">Ledger</span>
            </div></a>

            <a href="{{route('mis.store')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-circle-info"></i></span>
                <span class="menu-text">MIS</span>
            </div></a>

            <a href="{{route('admin.csps')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-user-plus"></i></span>
                <span class="menu-text">CSP Agents</span>
            </div></a>

            <a href="{{route('admin.allSubadmins')}}"><div class="menu-list-item">
                <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
                <span class="menu-text">Subadmins</span>
            </div></a>

        </ul>
    </div>
</div>

<!-- Open Wrap  -->
<div class="page-wrapper-theme">
