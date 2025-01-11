
			<div class="main-sidemenu">
				<div class="app-sidebar__user clearfix">
					<div class="dropdown user-pro-body">
						<div class="">
							<img alt="user-img" class="avatar avatar-xl brround" src="{{URL::asset('dashboard/img/faces/6.jpg')}}"><span class="avatar-status profile-status bg-green"></span>
						</div>
                        <div class="user-info">
                            <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
                            <span class="mb-0 text-muted">{{ Auth::user()->email }}</span>
                        </div>
					</div>
				</div>
				<ul class="side-menu">
					<li class="side-item side-item-category"></li>
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('Dashboard.viewer') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg><span class="side-menu__label">الرئيسية </a>
                    </li>

				
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('viewer.invoices.index') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3"/><path d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg><span class="side-menu__label">الاذون </a>
                    </li>
				

					<li class="slide">
                        <a class="side-menu__item" href="{{ route('viewer.invoices.prodact') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3"/><path d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg><span class="side-menu__label">منتجات   </a>
                    </li>
					<li class="slide">
						<a class="side-menu__item" data-toggle="slide" href="#"><svg
								xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0z" fill="none" />
								<path
									d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8c.28 0 .5-.22.5-.5 0-.16-.08-.28-.14-.35-.41-.46-.63-1.05-.63-1.65 0-1.38 1.12-2.5 2.5-2.5H16c2.21 0 4-1.79 4-4 0-3.86-3.59-7-8-7zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 10 6.5 10s1.5.67 1.5 1.5S7.33 13 6.5 13zm3-4C8.67 9 8 8.33 8 7.5S8.67 6 9.5 6s1.5.67 1.5 1.5S10.33 9 9.5 9zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 6 14.5 6s1.5.67 1.5 1.5S15.33 9 14.5 9zm4.5 2.5c0 .83-.67 1.5-1.5 1.5s-1.5-.67-1.5-1.5.67-1.5 1.5-1.5 1.5.67 1.5 1.5z"
									opacity=".3" />
								<path
									d="M12 2C6.49 2 2 6.49 2 12s4.49 10 10 10c1.38 0 2.5-1.12 2.5-2.5 0-.61-.23-1.21-.64-1.67-.08-.09-.13-.21-.13-.33 0-.28.22-.5.5-.5H16c3.31 0 6-2.69 6-6 0-4.96-4.49-9-10-9zm4 13h-1.77c-1.38 0-2.5 1.12-2.5 2.5 0 .61.22 1.19.63 1.65.06.07.14.19.14.35 0 .28-.22.5-.5.5-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.14 8 7c0 2.21-1.79 4-4 4z" />
								<circle cx="6.5" cy="11.5" r="1.5" />
								<circle cx="9.5" cy="7.5" r="1.5" />
								<circle cx="14.5" cy="7.5" r="1.5" />
								<circle cx="17.5" cy="11.5" r="1.5" />
							</svg><span class="side-menu__label">التقارير </span><i class="angle fe fe-chevron-down"></i></a>
						<ul class="slide-menu">
							<li><a class="slide-item" href="{{ route('viewer.invoices.create') }}">بحث عن سيريال </a></li>

							<li><a class="slide-item" href="{{ route('viewer.report.index') }}">تقرير الاذون</a></li>
							<li><a class="slide-item" href="{{ route('viewer.report.inventory') }}">تقرير مخزنى </a></li>
		
						</ul>
					</li>
				
					
					<li class="slide">
						<span class="side-menu__label">
						<a class=""  > 
							
				    
							<form method="get" action="{{ route('logout.admin') }}">
								<a class="dropdown-item" href="#"  onclick="event.preventDefault(); this.closest('form').submit();">
									<i class="bx bx-log-out"></i> تسجيل الخروج
								</a>
							</form>
						
						</a>
					</li>
				</ul>
			</div>
		</aside>
<!-- main-sidebar -->
