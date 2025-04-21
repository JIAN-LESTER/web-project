<!-- Sidebar Header -->
<div class="sidebar-header border-bottom d-flex align-items-center justify-content-between px-3 py-2">
    <div class="d-flex align-items-center gap-2">
      <!-- Logo -->
      <svg class="sidebar-brand-full" width="88" height="40" alt="CoreUI Logo">
        <use xlink:href="assets/brand/coreui.svg#full"></use>
      </svg>
      <span class="fw-bold text-white" style="font-size: 1rem;">Acad Bot</span>
    </div>

    <!--  Close Button (on small screens) -->
    {{-- <button class="btn-close d-lg-none" type="button" aria-label="Close"
      onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).hide()">
    </button> --}}
  </div>

  <!-- Sidebar Navigation -->
  <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    <!-- Theme -->
    <li class="nav-title">Quick Access</li>

    <li class="nav-item">
      <a class="nav-link" href="index.html">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-speedometer.svg" alt="Dashboard" />
        Dashboard
      </a>
      <a class="nav-link" href="colors.html">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-drop.svg" alt="Knowledge Base" />
        Knowledge Base
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="typography.html">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-notes.svg" alt="Reports" />
        Reports
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="typography.html">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-speech.svg" alt="ChatBot" />
        ChatBot
      </a>
    </li>

    <!-- Preferences -->
    <li class="nav-title">Preferences</li>

    <!-- Users Group -->
    <li class="nav-group">
      <a class="nav-link nav-group-toggle" href="#">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-user.svg" alt="Users" />
        Users
      </a>
      <ul class="nav-group-items">
        <li class="nav-item"><a class="nav-link" href="base/accordion.html">Sample1</a></li>
        <li class="nav-item"><a class="nav-link" href="base/cards.html">Sample2</a></li>
        <li class="nav-item"><a class="nav-link" href="base/collapse.html">Sample3</a></li>
        <li class="nav-item"><a class="nav-link" href="base/tables.html">Sample4</a></li>
      </ul>
    </li>

    <!-- History Group -->
    <li class="nav-group">
      <a class="nav-link nav-group-toggle" href="#">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-history.svg" alt="History" />
        History
      </a>
      <ul class="nav-group-items">
        <li class="nav-item"><a class="nav-link" href="buttons/buttons.html">System Logs</a></li>
        <li class="nav-item"><a class="nav-link" href="buttons/dropdowns.html">ChatBot History</a></li>
      </ul>
    </li>

    <!-- Charts -->
    <li class="nav-item">
      <a class="nav-link" href="charts.html">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-chart-pie.svg" alt="Charts" />
        Charts
      </a>
    </li>

    <!-- Forms Group -->
    <li class="nav-group">
      <a class="nav-link nav-group-toggle" href="#">
        <img class="nav-icon" src="vendors/@coreui/icons/svg/free/cil-notes.svg" alt="Forms" />
        Forms
      </a>
      <ul class="nav-group-items">
        <li class="nav-item"><a class="nav-link" href="forms/form-control.html">Form Control</a></li>
        <li class="nav-item"><a class="nav-link" href="forms/select.html">Select</a></li>
        <li class="nav-item"><a class="nav-link" href="forms/checks-radios.html">Checks & Radios</a></li>
        <li class="nav-item"><a class="nav-link" href="forms/range.html">Range</a></li>
      </ul>
    </li>
  </ul>

  <!-- ✅ Sidebar Styling -->
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
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.05);
    }

    .nav-title {
      color: #bbb;
      padding: 0.75rem 1rem 0.25rem;
      font-weight: 600;
      font-size: 0.75rem;
      text-transform: uppercase;
    }

    /* White X button override */
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
