<!-- Sidebar Header -->
<div class="sidebar-header border-bottom d-flex align-items-center justify-content-between px-3 py-2">
  <div class="d-flex align-items-center gap-2">
    <!-- Logo -->
    <svg class="sidebar-brand-full" width="88" height="40" alt="CoreUI Logo">
      <use xlink:href="assets/brand/coreui.svg#full"></use>
    </svg>
    <span class="fw-bold text-white" style="font-size: 1rem;">Acad Bot</span>
  </div>

  @php
    $user = Auth::user();
  @endphp

  <!-- Close Button (on small screens) -->
  <button class="btn-close d-lg-none" type="button" aria-label="Close"
    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).hide()">
  </button>
</div>

<!-- Sidebar Navigation -->
<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
  
  @if ($user->role === 'admin')
    <!-- Admin Navigation -->
    <li class="nav-title">Quick Access</li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
         href="{{ route('admin.dashboard') }}"
         aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : '' }}">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-speedometer.svg" alt="Dashboard" />
        Dashboard
      </a>

      <a class="nav-link {{ request()->routeIs('admin.knowledge_base') ? 'active' : '' }}"
         href="{{ route('admin.knowledge_base') }}"
         aria-current="{{ request()->routeIs('admin.knowledge_base') ? 'page' : '' }}">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-drop.svg" alt="Knowledge Base" />
        Knowledge Base
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin.reports_analytics') ? 'active' : '' }}"
         href="{{ route('admin.reports_analytics') }}"
         aria-current="{{ request()->routeIs('admin.reports_analytics') ? 'page' : '' }}">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-notes.svg" alt="Reports" />
        Reports
      </a>
    </li>

    <li class="nav-group">
      <a class="nav-link {{ request()->routeIs('admin.user_management') ? 'active' : '' }}"
         href="{{ route('admin.user_management') }}"
         aria-current="{{ request()->routeIs('admin.user_management') ? 'page' : '' }}">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-user.svg" alt="Users" />
        Users
      </a>
    </li>

    <!-- Preferences -->
    <li class="nav-title">Preferences</li>

    <li class="nav-group">
      <a class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}"
         href="{{ route('admin.logs') }}"
         aria-current="{{ request()->routeIs('admin.logs') ? 'page' : '' }}">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-history.svg" alt="Logs" />
        Logs
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('admin.charts') ? 'active' : '' }}"
         href="{{ route('admin.charts') }}"
         aria-current="{{ request()->routeIs('admin.charts') ? 'page' : '' }}">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-chart-pie.svg" alt="Charts" />
        Charts
      </a>
    </li>

    <li class="nav-group">
      <a class="nav-link {{ request()->routeIs('admin.forms') ? 'active' : '' }}"
         href="{{ route('admin.forms') }}"
         aria-current="{{ request()->routeIs('admin.forms') ? 'page' : '' }}">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-notes.svg" alt="Forms" />
        Forms
      </a>
    </li>

  @else
    <!-- User Navigation -->
    <li class="nav-title">Chat History</li>
    @php
      $conversations = \App\Models\Conversation::where('userID', $user->userID)
                        
                        ->latest('updated_at')
                        ->take(10)
                        ->orderByDesc('conversationID')
                        ->get();
    @endphp

    @forelse ($conversations as $conversation)
      <li class="nav-item">
      <a href="#"
   class="nav-link load-conversation flex-column align-items-start"
   data-id="{{ $conversation->conversationID }}">
  <small class="text-muted">Conversation #{{ $conversation->conversationID }}</small>
  <span>{{ Str::limit($conversation->conversation_title, 50) }}</span>
</a>
      </li>
    @empty
      <li class="nav-item">
        <span class="nav-link text-muted">No conversations yet</span>
      </li>
    @endforelse
  @endif

</ul>

<!-- Sidebar Styling -->
<style>
  .nav-icon {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.5rem;
    vertical-align: middle;
    filter: brightness(0) invert(1); /* makes icon white */
  }

  .nav-link {
    display: flex;
    align-items: center;
    color: #fff;
    transition: background-color 0.2s ease;
  }

  .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.05);
  }

  .nav-link.active {
    background-color: rgba(255, 255, 255, 0.1);
    font-weight: bold;
  }

  .nav-title {
    color: #bbb;
    padding: 0.75rem 1rem 0.25rem;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
  }

  .btn-close {
    position: relative;
    width: 1.5rem;
    height: 1.5rem;
    background: none;
    border: none;
    padding: 0;
    opacity: 1;
    filter: none;
  }

  .btn-close::before {
    content: '×';
    font-size: 1.5rem;
    color: white;
    display: inline-block;
    line-height: 1;
  }

  .btn-close > svg {
    display: none;
  }
</style>
