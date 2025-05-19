<!-- Sidebar Header -->
<div class="sidebar-header border-bottom d-flex align-items-center justify-content-between px-3 py-2">
  <div class="d-flex align-items-center gap-2">
    <!-- Logo -->
    <span class="fw-bold text-white" style="font-size: 1rem; height:40px;">OASP Assist</span>
  </div>

  @php
    $user = Auth::user();
  @endphp

  <!-- Close Button (on small screens) - Updated to use toggleSidebar -->
  <button class="btn-close d-lg-none" type="button" aria-label="Close" onclick="toggleSidebar()">
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
        <img class="nav-icon" src="{{ asset('vendors/@coreui/icons/svg/free/cil-speedometer.svg') }}" alt="Dashboard"
             onerror="this.onerror=null; this.style.backgroundImage='url(\'data:image/svg+xml,%3Csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' viewBox=\\\'0 0 512 512\\\'%3E%3Cpath fill=\\\'white\\\' d=\\\'M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z\\\'/%3E%3C/svg%3E\')'; this.style.backgroundSize='contain'; this.style.backgroundRepeat='no-repeat'; this.style.backgroundPosition='center';"
        />
        Dashboard
      </a>

    

      <a class="nav-link {{ request()->routeIs('admin.reports_analytics') ? 'active' : '' }}"
         href="{{ route('admin.reports_analytics') }}"
         aria-current="{{ request()->routeIs('admin.reports_analytics') ? 'page' : '' }}">
        <img class="nav-icon" src="{{ asset('vendors/@coreui/icons/svg/free/cil-chart.svg') }}" alt="Reports"
             onerror="this.onerror=null; this.style.backgroundImage='url(\'data:image/svg+xml,%3Csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' viewBox=\\\'0 0 512 512\\\'%3E%3Cpath fill=\\\'white\\\' d=\\\'M332.8 320h38.4c6.4 0 12.8-6.4 12.8-12.8V172.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v134.4c0 6.4 6.4 12.8 12.8 12.8zm96 0h38.4c6.4 0 12.8-6.4 12.8-12.8V76.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v230.4c0 6.4 6.4 12.8 12.8 12.8zm-288 0h38.4c6.4 0 12.8-6.4 12.8-12.8v-70.4c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v70.4c0 6.4 6.4 12.8 12.8 12.8zm96 0h38.4c6.4 0 12.8-6.4 12.8-12.8V108.8c0-6.4-6.4-12.8-12.8-12.8h-38.4c-6.4 0-12.8 6.4-12.8 12.8v198.4c0 6.4 6.4 12.8 12.8 12.8zM496 384H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v336c0 17.67 14.33 32 32 32h464c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z\\\'/%3E%3C/svg%3E\')'; this.style.backgroundSize='contain'; this.style.backgroundRepeat='no-repeat'; this.style.backgroundPosition='center';"
        />
        Reports
      </a>
    </li>
    <li class="nav-title">Management</li>

    <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.knowledge_base') ? 'active' : '' }}"
         href="{{ route('admin.knowledge_base') }}"
         aria-current="{{ request()->routeIs('admin.knowledge_base') ? 'page' : '' }}">
        <img class="nav-icon" src="{{ asset('vendors/@coreui/icons/svg/free/cil-book.svg') }}" alt="Knowledge Base"
             onerror="this.onerror=null; this.style.backgroundImage='url(\'data:image/svg+xml,%3Csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' viewBox=\\\'0 0 448 512\\\'%3E%3Cpath fill=\\\'white\\\' d=\\\'M448 360V24c0-13.3-10.7-24-24-24H96C43 0 0 43 0 96v320c0 53 43 96 96 96h328c13.3 0 24-10.7 24-24v-16c0-7.5-3.5-14.3-8.9-18.7-4.2-15.4-4.2-59.3 0-74.7 5.4-4.3 8.9-11.1 8.9-18.6zM128 134c0-3.3 2.7-6 6-6h212c3.3 0 6 2.7 6 6v20c0 3.3-2.7 6-6 6H134c-3.3 0-6-2.7-6-6v-20zm0 64c0-3.3 2.7-6 6-6h212c3.3 0 6 2.7 6 6v20c0 3.3-2.7 6-6 6H134c-3.3 0-6-2.7-6-6v-20zm253.4 250H96c-17.7 0-32-14.3-32-32 0-17.6 14.4-32 32-32h285.4c-1.9 17.1-1.9 46.9 0 64z\\\'/%3E%3C/svg%3E\')'; this.style.backgroundSize='contain'; this.style.backgroundRepeat='no-repeat'; this.style.backgroundPosition='center';"
        />
        Knowledge Base
      </a>

      <a class="nav-link {{ request()->routeIs('admin.user_management') ? 'active' : '' }}"
         href="{{ route('admin.user_management') }}"
         aria-current="{{ request()->routeIs('admin.user_management') ? 'page' : '' }}">
        <img class="nav-icon" src="{{ asset('vendors/@coreui/icons/svg/free/cil-user.svg') }}" alt="Users"
             onerror="this.onerror=null; this.style.backgroundImage='url(\'data:image/svg+xml,%3Csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' viewBox=\\\'0 0 448 512\\\'%3E%3Cpath fill=\\\'white\\\' d=\\\'M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z\\\'/%3E%3C/svg%3E\')'; this.style.backgroundSize='contain'; this.style.backgroundRepeat='no-repeat'; this.style.backgroundPosition='center';"
        />
        Users
      </a>
    </li>

    <!-- Preferences -->
    <li class="nav-title">System</li>

    <li class="nav-group">
      <a class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}"
         href="{{ route('admin.logs') }}"
         aria-current="{{ request()->routeIs('admin.logs') ? 'page' : '' }}">
        <img class="nav-icon" src="{{ asset('vendors/@coreui/icons/svg/free/cil-history.svg') }}" alt="Logs"
             onerror="this.onerror=null; this.style.backgroundImage='url(\'data:image/svg+xml,%3Csvg xmlns=\\\'http://www.w3.org/2000/svg\\\' viewBox=\\\'0 0 512 512\\\'%3E%3Cpath fill=\\\'white\\\' d=\\\'M504 255.531c.253 136.64-111.18 248.372-247.82 248.468-59.015.042-113.223-20.53-155.822-54.911-11.077-8.94-11.905-25.541-1.839-35.607l11.267-11.267c8.609-8.609 22.353-9.551 31.891-1.984C173.062 425.135 212.781 440 256 440c101.705 0 184-82.311 184-184 0-101.705-82.311-184-184-184-48.814 0-93.149 18.969-126.068 49.932l50.754 50.754c10.08 10.08 2.941 27.314-11.313 27.314H24c-8.837 0-16-7.163-16-16V38.627c0-14.254 17.234-21.393 27.314-11.314l49.372 49.372C129.209 34.136 189.552 8 256 8c136.81 0 247.747 110.78 248 247.531zm-180.912 78.784l9.823-12.63c8.138-10.463 6.253-25.542-4.21-33.679L288 256.349V152c0-13.255-10.745-24-24-24h-16c-13.255 0-24 10.745-24 24v135.651l65.409 50.874c10.463 8.137 25.541 6.253 33.679-4.21z\\\'/%3E%3C/svg%3E\')'; this.style.backgroundSize='contain'; this.style.backgroundRepeat='no-repeat'; this.style.backgroundPosition='center';"
        />
        Logs
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

  
  <span style="display:block; white-space:normal; word-wrap:break-word; overflow-wrap:break-word;">
    {{ Str::limit($conversation->conversation_title, 50) }}
</span>

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
