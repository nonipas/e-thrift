<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li class="mm-active">
                    <a href="{{url('/')}}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboards</span>
                    </a>

                </li>
                @if(Auth::user()->hasPermission('manage-user'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-dashboards">Users</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('user.index')}}" key="t-user-list">User List</a></li>
                        <li><a href="{{route('user.add')}}" key="t-add-user">Add Users</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasPermission('manage-member'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-group"></i>
                        <span key="t-dashboards">Members</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('member.index')}}" key="t-default">Members list</a></li>
                        <li><a href="{{route('member.add')}}" key="t-saas">Add member</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasPermission('manage-contribution') || Auth::user()->hasPermission('approve-contribution'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-layer"></i>
                        <span key="t-con">Contributions</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li class="{{Auth::user()->hasPermission('manage-contribution') ?'':'d-none'}}"><a href="{{route('contribution.add')}}" key="t-add-cont">Add New Contribution</a></li>
                        <li><a href="{{route('contribution.index')}}" key="t-list-cont">Contribution List</a></li>
                        <li class="{{Auth::user()->hasPermission('manage-contribution') ?'':'d-none'}}"><a href="{{route('contribution.generate')}}" key="t-gm-cont">Generate Monthly Contribution</a></li>
                        <li class="{{Auth::user()->hasPermission('approve-contribution') ?'':'d-none'}}"><a href="{{route('contribution.approve')}}" key="t-am-cont">Approve Monthly Contribution</a></li>
                        <li><a href="{{route('contribution.monthly_list')}}" key="t-mlist-cont"> Monthly Contribution list</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasPermission('manage-dividend') || Auth::user()->hasPermission('approve-dividend'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-calculator"></i>
                        <span key="t-dividend">Dividend</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li class="{{Auth::user()->hasPermission('manage-dividend') ?'':'d-none'}}"><a href="{{route('dividend.generate')}}" key="t-gen-dividend">Generate Annual Dividend</a></li>

                        <li><a href="{{route('dividend.index')}}" key="t-gen-divend">Dividend List</a></li>
                        <li class="{{Auth::user()->hasPermission('approve-dividend') ?'':'d-none'}}"><a href="{{route('dividend.approve')}}" key="t-gen-divend">Approve Dividend</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasPermission('manage-loan') || Auth::user()->hasPermission('approve-repayment') || Auth::user()->hasPermission('manage-repayment'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-bank"></i>
                        <span key="t-loan">Loan</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li class="{{Auth::user()->hasPermission('manage-loan') ?'':'d-none'}}"><a href="{{route('loan.add')}}" key="t-add-loan">Add New Loan</a></li>
                        <li><a href="{{route('loan.index')}}" key="t-list-loan">Loan List</a></li>
                        <li class="{{Auth::user()->hasPermission('manage-repayment') ?'':'d-none'}}"><a href="{{route('loan.generate')}}" key="t-gm-loan">Generate Monthly Repayment</a></li>
                        <li class="{{Auth::user()->hasPermission('approve-repayment') ?'':'d-none'}}"><a href="{{route('loan.approve')}}" key="t-am-loan">Approve Monthly Repayment</a></li>
                        <li><a href="{{route('loan.repayment')}}" key="t-mlist-loan"> Monthly Repayment list</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasPermission('manage-payment') || Auth::user()->hasPermission('approve-payment') || Auth::user()->hasPermission('process-payment'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-money"></i>
                        <span key="t-pay">Payment</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li class="{{Auth::user()->hasPermission('manage-payment') ?'':'d-none'}}"><a href="{{route('payment.create-batch')}}" key="t-pay-batch">Add New Batch</a></li>
                        <li class="{{Auth::user()->hasPermission('manage-payment') ?'':'d-none'}}"><a href="{{route('payment.batch')}}" key="t-pay-batch-list">Batch List</a></li>
                        <li class="{{Auth::user()->hasPermission('approve-payment') ?'':'d-none'}}"><a href="{{route('payment.approve_batches')}}" key="t-pay-a-batch">Approve Batch</a></li>
                        <li class="{{Auth::user()->hasPermission('process-payment') ?'':'d-none'}}"><a href="{{route('payment.process_batches')}}" key="t-pay-p-list">Process Batch</a></li>
                        <li><a href="{{route('payment.index')}}" key="t-pay-history">Payment History</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->hasRole(0))
                <li class="menu-title" key="t-admin">Admin Settings</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-setting">Settings
                        </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{route('role.index')}}" key="t-role">Roles</a></li>
                        <li><a href="{{route('setting.permission')}}" key="t-permission">Permissions</a></li>
                        <li><a href="{{route('setting.payment_category')}}" key="t-payment-category">Payment category</a></li>
                        <li><a href="{{route('setting.index')}}" key="t-general">General Settings</a></li>
                        
                    </ul>
                </li>
                @endif
                <li class="menu-title" key="t-hr"><hr></li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-history"></i>
                        <span key="t-audit">History & Reports
                        </span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                    </ul>
                </li>
                <li>
                    <a href="{{route('logout')}}" class="waves-effect">
                        <i class="bx bx-log-out-circle"></i>
                        <span key="t-logout">logout
                        </span>
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>