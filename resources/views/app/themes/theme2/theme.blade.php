<!doctype html>
<!--[if lte IE 9]>     <html lang="es" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="es" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>@yield('title')</title>

        <meta name="description" content="{{ config_var('Sistema.Nombre') }}">
        <meta name="author" content="Instituto Tecnológico de Chetumal 2017 - 2018">
        <meta name="robots" content="noindex, nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Open Graph Meta -->
        <!-- Open Graph Meta -->
        <meta property="og:site_name" content="{{ config_var('Sistema.Nombre') }}">
        <meta property="og:description" content="Instituto Tecnológico de Chetumal 2017 - 2018">
        <meta property="og:type" content="webapp">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset('img/favicon/favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/favicon/favicon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon-180x180.png') }}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Page JS Plugins CSS -->
        {{ Html::style('js/plugins/sweetalert2/sweetalert2.min.css') }}
        
        @stack('css-style')

        <!-- Codebase framework -->
        {{ Html::style('css/codebase.min.css',['id'=>'css-main']) }}
        {{ Html::style('css/custom.codebase.css') }}

        <!-- END Stylesheets -->
        @stack('css-custom')

    </head>
    <body>
        <!-- Page Container -->
        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-modern main-content-boxe">
            <nav id="sidebar">
                <div class="sidebar-content">
                    <!-- Side Header -->
                    <div class="content-header content-header-fullrow px-15">
                        <!-- Mini Mode -->
                        <div class="content-header-section sidebar-mini-visible-b">
                            <!-- Logo -->
                            <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                                <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                            </span>
                            <!-- END Logo -->
                        </div>
                        <!-- END Mini Mode -->

                        <!-- Normal Mode -->
                        <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                            <!-- Close Sidebar, Visible only on mobile screens -->
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                            <!-- END Close Sidebar -->

                            <!-- Logo -->
                            <div class="content-header-item">
                                <a class="link-effect font-w700" href="/">
                                    <i class="si si-fire text-primary"></i>
                                    <span class="font-size-xl text-dual-primary-dark">OS</span><span class="font-size-xl text-primary">IMJUVE</span>
                                </a>
                            </div>
                            <!-- END Logo -->
                        </div>
                        <!-- END Normal Mode -->
                    </div>
                    <!-- END Side Header -->

                    <!-- Side User -->
                    <div class="content-side content-side-full content-side-user px-10 align-parent">
                        <!-- Visible only in mini mode -->
                        <div class="sidebar-mini-visible-b align-v animated fadeIn">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar15.jpg" alt="">
                        </div>
                        <!-- END Visible only in mini mode -->

                        <!-- Visible only in normal mode -->
                        <div class="sidebar-mini-hidden-b text-center">
                            <a class="img-link" href="be_pages_generic_profile.html">

                                {!! user()->presenter()->imgAvatarSmall() !!}
                                <!--img class="img-avatar" src="assets/media/avatars/avatar15.jpg" alt=""-->
                            </a>
                            <ul class="list-inline mt-10">
                                <li class="list-inline-item">
                                    <a class="link-effect text-dual-primary-dark font-size-xs font-w600 text-uppercase" href="/">
                                        {{ user()->UsuarioDetalle->presenter()->getNombreCompleto() }}
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                    <a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle"  href="{{ url('usuario/perfil') }}">
                                        <i class="si si-note"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="link-effect text-dual-primary-dark" href="{{ route('logout') }}">
                                        <i class="si si-logout"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END Visible only in normal mode -->
                    </div>
                    <!-- END Side User -->

                    <!-- Side Navigation -->
                    <div class="content-side content-side-full">
                        <ul class="nav-main">
                            <li>
                                <a class="active" href="be_pages_dashboard.html"><i class="si si-cup"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                            </li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-badge"></i><span class="sidebar-mini-hide">Afiliaciones</span></a>
                                <ul>
                                    <li>
                                        <a href="imjuve/afiliacion/">Afiliados</a>
                                    
                                    </li>
                                    
                                </ul>
                            </li>
                            <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Configuración</span></li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Catalogos</span></a>
                                <ul>
                                    <li>
                                        <a href="be_blocks.html">Actividades</a>
                                    </li>
                                    <li>
                                        <a href="be_blocks_tiles.html">Generos</a>
                                    </li>
                                    <li>
                                        <a href="be_blocks_draggable.html">Draggable</a>
                                    </li>
                                    <li>
                                        <a href="be_blocks_api.html">API</a>
                                    </li>
                                </ul>
                            </li>
                          }
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-moustache"></i><span class="sidebar-mini-hide">Usuarios</span></a>
                                <ul>
                                    <li>
                                        <a href="/configuracion/usuarios">Ver usuarios</a>
                                    </li>
                                    <li>
                                        <a href="/configuracion/usuarios/permisos-asignaciones">Permisos</a>
                                    </li>
                                    
                                </ul>
                            </li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-energy"></i><span class="sidebar-mini-hide">Elements</span></a>
                                <ul>
                                    <li>
                                        <a href="be_ui_grid.html">Grid</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_icons.html">Icons</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_typography.html">Typography</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_activity.html">Activity</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_buttons.html">Buttons</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_navigation.html">Navigation</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_tabs.html">Tabs</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_modals_tooltips.html">Modals &amp; Tooltips</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_images.html">Images</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_animations.html">Animations</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_ribbons.html">Ribbons</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_timeline.html">Timeline</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_accordion.html">Accordion</a>
                                    </li>
                                    <li>
                                        <a href="be_ui_color_themes.html">Color Themes</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-layers"></i><span class="sidebar-mini-hide">Tables</span></a>
                                <ul>
                                    <li>
                                        <a href="be_tables_styles.html">Styles</a>
                                    </li>
                                    <li>
                                        <a href="be_tables_responsive.html">Responsive</a>
                                    </li>
                                    <li>
                                        <a href="be_tables_helpers.html">Helpers</a>
                                    </li>
                                    <li>
                                        <a href="be_tables_pricing.html">Pricing</a>
                                    </li>
                                    <li>
                                        <a href="be_tables_datatables.html">DataTables</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-note"></i><span class="sidebar-mini-hide">Forms</span></a>
                                <ul>
                                    <li>
                                        <a href="be_forms_elements_bootstrap.html">Bootstrap Elements</a>
                                    </li>
                                    <li>
                                        <a href="be_forms_elements_material.html">Material Elements</a>
                                    </li>
                                    <li>
                                        <a href="be_forms_css_inputs.html">CSS Inputs</a>
                                    </li>
                                    <li>
                                        <a href="be_forms_plugins.html">Plugins</a>
                                    </li>
                                    <li>
                                        <a href="be_forms_editors.html">Editors</a>
                                    </li>
                                    <li>
                                        <a href="be_forms_validation.html">Validation</a>
                                    </li>
                                    <li>
                                        <a href="be_forms_wizard.html">Wizard</a>
                                    </li>
                                    <li>
                                        <a href="be_forms_premade.html">Pre-made</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-main-heading"><span class="sidebar-mini-visible">BD</span><span class="sidebar-mini-hidden">Build</span></li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-vector"></i><span class="sidebar-mini-hide">Layout</span></a>
                                <ul>
                                    <li>
                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">General</a>
                                        <ul>
                                            <li>
                                                <a href="be_layout_default.html">Default</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_flipped.html">Flipped</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_native_scrolling.html">Native Scrolling</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">Header</a>
                                        <ul>
                                            <li>
                                                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Static</a>
                                                <ul>
                                                    <li>
                                                        <a href="be_layout_header_modern.html">Modern</a>
                                                    </li>
                                                    <li>
                                                        <a href="be_layout_header_classic.html">Classic</a>
                                                    </li>
                                                    <li>
                                                        <a href="be_layout_header_classic_inverse.html">Classic Inverse</a>
                                                    </li>
                                                    <li>
                                                        <a href="be_layout_header_glass.html">Glass</a>
                                                    </li>
                                                    <li>
                                                        <a href="be_layout_header_glass_inverse.html">Glass Inverse</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Fixed</a>
                                                <ul>
                                                    <li>
                                                        <a href="be_layout_header_fixed_modern.html">Modern</a>
                                                    </li>
                                                    <li>
                                                        <a href="be_layout_header_fixed_classic.html">Classic</a>
                                                    </li>
                                                    <li>
                                                        <a href="be_layout_header_fixed_classic_inverse.html">Classic Inverse</a>
                                                    </li>
                                                    <li>
                                                        <a href="be_layout_header_fixed_glass.html">Glass</a>
                                                    </li>
                                                    <li>
                                                        <a href="be_layout_header_fixed_glass_inverse.html">Glass Inverse</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sidebar</a>
                                        <ul>
                                            <li>
                                                <a href="be_layout_sidebar_inverse.html">Inverse</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_sidebar_hidden.html">Hidden</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_sidebar_mini.html">Mini</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">Side Overlay</a>
                                        <ul>
                                            <li>
                                                <a href="be_layout_side_overlay_visible.html">Visible</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_side_overlay_hoverable.html">Hoverable</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_side_overlay_no_page_overlay.html">No Page Overlay</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">Main Content</a>
                                        <ul>
                                            <li>
                                                <a href="be_layout_content_boxed.html">Boxed</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_content_narrow.html">Narrow</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_content_full_width.html">Full Width</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">Hero</a>
                                        <ul>
                                            <li>
                                                <a href="be_layout_hero_color.html">Color</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_hero_bubbles.html">Bubbles</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_hero_image.html">Image</a>
                                            </li>
                                            <li>
                                                <a href="be_layout_hero_video.html">Video</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="be_layout_api.html">API</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-trophy"></i><span class="sidebar-mini-hide">Components</span></a>
                                <ul>
                                    <li>
                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><span class="sidebar-mini-hide">Main Menu</span></a>
                                        <ul>
                                            <li>
                                                <a href="#">Link 1-1</a>
                                            </li>
                                            <li>
                                                <a href="#">Link 1-2</a>
                                            </li>
                                            <li>
                                                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 2</a>
                                                <ul>
                                                    <li>
                                                        <a href="#">Link 2-1</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Link 2-2</a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sub Level 3</a>
                                                        <ul>
                                                            <li>
                                                                <a href="#">Link 3-1</a>
                                                            </li>
                                                            <li>
                                                                <a href="#">Link 3-2</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">Chat</a>
                                        <ul>
                                            <li>
                                                <a href="be_comp_chat_multiple.html">Multiple</a>
                                            </li>
                                            <li>
                                                <a href="be_comp_chat_single.html">Single</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="be_comp_charts.html">Charts</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_nestable.html">Nestable</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_gallery.html">Gallery</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_sliders.html">Sliders</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_scrolling.html">Scrolling</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_rating.html">Rating</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_filtering.html">Filtering</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_appear.html">Appear</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_countto.html">CountTo</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_calendar.html">Calendar</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_image_cropper.html">Image Cropper</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_maps_google.html">Google Maps</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_maps_vector.html">Vector Maps</a>
                                    </li>
                                    <li>
                                        <a href="be_comp_syntax_highlighting.html">Syntax Highlighting</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-main-heading"><span class="sidebar-mini-visible">PG</span><span class="sidebar-mini-hidden">Pages</span></li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-globe-alt"></i><span class="sidebar-mini-hide">Generic</span></a>
                                <ul>
                                    <li>
                                        <a href="be_pages_generic_blank.html">Blank</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_blank_block.html">Blank (Block)</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_blank_breadcrumb.html">Blank (Breadcrumb)</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_scrumboard.html">Scrum Board</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_search.html">Search</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_profile.html">Profile</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_inbox.html">Inbox</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_invoice.html">Invoice</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_faq.html">FAQ</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_blog.html">Blog</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_story.html">Story</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_project_list.html">Project List</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_project.html">Project</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_upgrade_plan.html">Upgrade Plan</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_team.html">Team</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_contact.html">Contact</a>
                                    </li>
                                    <li>
                                        <a href="be_pages_generic_todo.html">Todo</a>
                                    </li>
                                    <li>
                                        <a href="op_coming_soon.html">Coming Soon</a>
                                    </li>
                                    <li>
                                        <a href="op_maintenance.html">Maintenance</a>
                                    </li>
                                    <li>
                                        <a href="op_status.html">Status</a>
                                    </li>
                                    <li>
                                        <a href="op_installation.html">Installation</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-lock"></i><span class="sidebar-mini-hide">Authentication</span></a>
                                <ul>
                                    <li>
                                        <a href="be_pages_auth_all.html">All</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_signin.html">Sign In</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_signin2.html">Sign In 2</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_signin3.html">Sign In 3</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_signup.html">Sign Up</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_signup2.html">Sign Up 2</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_signup3.html">Sign Up 3</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_lock.html">Lock Screen</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_lock2.html">Lock Screen 2</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_lock3.html">Lock Screen 3</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_reminder.html">Pass Reminder</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_reminder2.html">Pass Reminder 2</a>
                                    </li>
                                    <li>
                                        <a href="op_auth_reminder3.html">Pass Reminder 3</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-flag"></i><span class="sidebar-mini-hide">Error</span></a>
                                <ul>
                                    <li>
                                        <a href="be_pages_error_all.html">All</a>
                                    </li>
                                    <li>
                                        <a href="op_error_400.html">400</a>
                                    </li>
                                    <li>
                                        <a href="op_error_401.html">401</a>
                                    </li>
                                    <li>
                                        <a href="op_error_403.html">403</a>
                                    </li>
                                    <li>
                                        <a href="op_error_404.html">404</a>
                                    </li>
                                    <li>
                                        <a href="op_error_500.html">500</a>
                                    </li>
                                    <li>
                                        <a href="op_error_503.html">503</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- END Side Navigation -->
                </div>
            </nav>
            <!--nav id="sidebar">
                <div id="sidebar-scroll">
                    <div class="sidebar-content">
                        <div class="content-header content-header-fullrow bg-black-op-10">
                            <div class="content-header-section text-center align-parent">
                                <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                                <div class="content-header-item">
                                    <a class="link-effect font-w700" href="/">
                                        <span class="font-size-xl text-dual-primary-dark">
                                            {{ Html::image('img/favicon/logo.png','Logo',['class'=>'mb-5','width'=>'30']) }} {{ config_var('Sistema.Siglas') }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="content-side content-side-full">
                            <ul class="nav-main">
                                include('app.themes.theme2.menu')
                            </ul>
                        </div>
                    </div>
                </div>
            </nav-->

            <header id="page-header">
                <!--div>
                include('app.themes.theme2.header')
                </div-->
                <div id="page-header-loader" class="overlay-header bg-primary">
                    <div class="content-header content-header-fullrow text-center">
                        <div class="content-header-item">
                            <i class="fa fa-sun-o fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                
                <!-- User Info -->
                @yield('userInfo')
                <!-- END User Info -->
                
                <!-- Main Content -->
                <div class="content p-10">
                    @yield('content')
                </div>
                <!-- END Main Content -->

                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="opacity-0" style="opacity: 1;">
                <div class="content p-10 font-size-xs clearfix">
                    <div class="float-left">
                        <span class="font-w600 text-primary">{{ config_var('Sistema.Siglas') . ' ' . config_var('Sistema.Version') }}</span> &copy; <span class="js-year-copy">2020</span>
                    </div>
                    <div class="float-right">
                        Powered by <span class="font-w600 text-danger">Cpjbugg</span>
                    </div>
                </div>
            </footer>
            <!-- END Footer -->

        </div>
        <!-- END Page Container -->

        <!-- Codebase Core JS -->
        {{ Html::script('js/core/jquery.min.js') }}
        {{ Html::script('js/core/bootstrap.bundle.min.js') }}
        {{ Html::script('js/core/jquery.slimscroll.min.js') }}
        {{ Html::script('js/core/jquery-scrollLock.min.js') }}
        {{ Html::script('js/core/jquery.appear.min.js') }}
        {{ Html::script('js/core/js.cookie.min.js') }}
        {{ Html::script('js/plugins/vue/vue.min.js') }}
        {{ Html::script('js/plugins/vue/axios.min.js') }}
        {{ Html::script('js/codebase.js') }}
        {{ Html::script('js/app.js') }}

        {{ Html::script('js/plugins/jquery-validation/jquery.validate.min.js') }}
        {{-- Html::script('js/app-notifications.js') --}}
        {{ Html::script('js/app-alert.js') }}

        @stack('js-script')

        @stack('js-custom')
    </body>
</html>