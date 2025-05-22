<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- <title>OASP Assist</title> --}}
  <link rel="stylesheet" href="{{ asset('login_and_register/login.css') }}">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/css/coreui.min.css" rel="stylesheet"
    integrity="sha384-PDUiPu3vDllMfrUHnurV430Qg8chPZTNhY8RUpq89lq22R3PzypXQifBpcpE1eoB" crossorigin="anonymous">

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Fonts - Poppins (matching dashboard) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* Reset margin and overflow */
    html, body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    body {
      overflow-y: auto;
    }

    /* Color Variables */
    :root {
      --emerald-dark: #0F4C3A;         /* Rich Emerald Dark */
      --emerald-darker: #0A3628;       /* Darker shade for hover */
      --emerald-light: #8cd9bd;        /* Light emerald for accents */
      --navbar-dark-yellow: #E6B800;   /* Dark yellow for navbar */
      --navbar-dark-yellow-hover: #D4A900; /* Darker yellow for hover effects */
      --text-light: #e6e6e6;           /* Light text color */
      --text-lighter: #ffffff;         /* White text for hover states */
      --accent-highlight: #4ECDC4;     /* Accent color for highlights */
    }

    /* Base styling with improved typography - MATCHING DASHBOARD */
    body {
      background-color: #f5f5f5; /* Matching dashboard background */
      color: #333;
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      font-size: 13px; /* MATCHING DASHBOARD FONT SIZE */
    }

    .swal2-container {
      position: fixed !important;
      z-index: 10000000 !important;
      padding: 0 !important;
    }

    /* Make sure the modal dialog appears properly */
    .swal2-popup {
      position: relative !important;
      box-sizing: border-box !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: center !important;
      width: 32em !important;
      max-width: 100% !important;
      padding: 1.25em !important;
      border-radius: 5px !important;
      background: #fff !important;
      font-family: 'Poppins', inherit !important; /* MATCHING FONT */
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.4) !important;
    }

    /* Make sure the backdrop covers everything */
    .swal2-backdrop-show {
      background: rgba(0, 0, 0, 0.4) !important;
    }

    /* Stop page from scrolling when alert is shown */
    body.swal2-shown {
      overflow-y: hidden !important;
    }

    /* Override any container styles that might interfere */
    body.swal2-shown .container,
    body.swal2-shown .container-real,
    body.swal2-shown .wrapper {
      position: static !important;
    }

    .container-real {
      width: 100%;
      height: 100%;
      position: relative;
      display: flex;
      flex-direction: column;
    }

    /* Sidebar styling with Rich Emerald Dark */
    #sidebar {
      width: 240px;
      background-color: var(--emerald-dark);
      color: var(--text-light);
      padding: 0;
      margin: 0;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
      font-size: 13px; /* MATCHING DASHBOARD FONT SIZE */
    }

    /* Fixed sidebar for large screens */
    .sidebar-fixed {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 1030;
      transition: transform 0.3s ease;
    }

    /* Sidebar navigation styling - MATCHING DASHBOARD */
    .sidebar .nav-link {
      color: var(--text-light);
      padding: 0.75rem 1.25rem;
      border-left: 3px solid transparent;
      transition: all 0.2s ease;
      font-weight: 500;
      font-size: 13px; /* MATCHING DASHBOARD */
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
    }

    .sidebar .nav-link:hover {
      color: var(--text-lighter);
      background-color: var(--emerald-darker);
      border-left-color: var(--accent-highlight);
    }

    .sidebar .nav-link.active {
      color: var(--text-lighter);
      background-color: var(--emerald-darker);
      border-left-color: var(--accent-highlight);
    }

    .sidebar .nav-link i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    /* Sidebar header/brand area - MATCHING DASHBOARD */
    .sidebar-header {
      padding: 1.5rem 1rem;
      background-color: var(--emerald-darker);
      text-align: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-header h3 {
      color: var(--text-lighter);
      font-size: 1rem; /* ADJUSTED TO MATCH DASHBOARD SCALE */
      margin: 0;
      font-weight: 600;
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
    }

    /* Menu section headers - MATCHING DASHBOARD */
    .sidebar-section-header {
      color: var(--emerald-light);
      font-size: 0.75rem; /* ADJUSTED TO MATCH DASHBOARD SCALE */
      text-transform: uppercase;
      letter-spacing: 0.05rem;
      font-weight: 600;
      padding: 1rem 1.25rem 0.5rem;
      opacity: 0.8;
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
    }

    /* Content wrapper for push layout */
    .wrapper {
      transition: margin-left 0.3s ease, width 0.3s ease;
      margin: 0;
      padding: 0;
      max-width: 100%;
      overflow-x: hidden;
      min-height: 100vh;
      padding-bottom: 50px; /* Add margin at the bottom */
    }

    /* Large screen behavior (≥992px) */
    @media (min-width: 992px) {
      .wrapper {
        margin-left: 240px;
        width: calc(100% - 240px);
      }

      body.sidebar-hidden .wrapper {
        margin-left: 0;
        width: 100%;
      }

      body.sidebar-hidden #sidebar {
        transform: translateX(-100%);
      }
    }

    /* Small screen behavior (<992px) */
    @media (max-width: 991.98px) {
      #sidebar {
        transform: translateX(-100%);
      }

      #sidebar.show {
        transform: translateX(0);
        z-index: 1040;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      }

      .wrapper {
        margin-left: 0 !important;
        width: 100%;
      }

      .sidebar-backdrop {
        content: "";
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1035;
        display: none;
      }

      .sidebar-backdrop.show {
        display: block;
      }
    }

    /* Navbar styling - Dark Yellow - MATCHING DASHBOARD */
    .header {
      margin: 0 !important;
      padding: 0 !important;
      width: 100% !important;
      max-width: 100% !important;
      overflow: visible !important;
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      right: 0 !important;
      z-index: 1500 !important;
      transition: left 0.3s ease, width 0.3s ease;
      background-color: var(--navbar-dark-yellow) !important;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
      font-size: 13px; /* MATCHING DASHBOARD */
    }

    .header .container-fluid {
      margin: 0 !important;
      padding: 0 !important;
      border: none !important;
      width: 100% !important;
      max-width: 100% !important;
      overflow: visible !important;
      position: relative;
      z-index: 1500 !important;
    }

    .header-nav {
      margin: 0 !important;
      padding: 0 !important;
      position: relative;
      z-index: 1500 !important;
    }

    /* Header button styling - MATCHING DASHBOARD */
    .header-toggler {
      background: transparent;
      border: none;
      color: #333;
      cursor: pointer;
      padding: 0.375rem 0.75rem;
      transition: all 0.2s ease;
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
    }

    .header-toggler:hover {
      background-color: var(--navbar-dark-yellow-hover);
      border-radius: 4px;
    }

    /* New Chat button styling - MATCHING DASHBOARD */
    .btn-outline-primary {
      border-color: #ffffff;
      color: #2c3e50;
      background-color: #ffffff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
      font-size: 13px; /* MATCHING DASHBOARD */
      font-weight: 500; /* MATCHING DASHBOARD */
    }

    .btn-outline-primary:hover {
      background-color: #f8f9fa;
      border-color: #e9ecef;
      color: #2c3e50;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .mb-4 {
      margin-bottom: 0 !important;
    }

    /* Container width fix - MATCHING DASHBOARD */
    .container {
      max-width: 100%;
      padding: 1rem;
      margin: 0;
      padding-top: 56px; /* Add padding for fixed header */
      margin-bottom: 50px; /* Add bottom margin */
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
      font-size: 13px; /* MATCHING DASHBOARD */
    }

    /* Dropdown styling to match the theme - MATCHING DASHBOARD */
    .dropdown-menu {
      z-index: 2000 !important;
      min-width: 10rem;
      padding: 0.5rem 0;
      border: 1px solid rgba(0,0,0,.15);
      border-radius: 0.25rem;
      margin-top: 0.125rem;
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
      background-color: #fff;
      position: absolute !important;
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
      font-size: 13px; /* MATCHING DASHBOARD */
    }

    .header .dropdown-menu {
      position: absolute !important;
      z-index: 2000 !important;
    }

    .header .nav-link {
      position: relative;
      z-index: 1500 !important;
      color: #333;
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
      font-size: 13px; /* MATCHING DASHBOARD */
    }

    .dropdown-item {
      display: block;
      width: 100%;
      padding: 0.5rem 1.5rem;
      clear: both;
      color: #212529;
      text-align: inherit;
      white-space: nowrap;
      background-color: transparent;
      border: 0;
      font-size: 13px; /* MATCHING DASHBOARD */
      transition: background-color 0.2s ease;
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
    }

    .dropdown-item:hover {
      background-color: #f8f9fa;
      color: #16181b;
    }

    .dropdown-item i {
      margin-right: 8px;
      width: 16px;
      text-align: center;
    }

    .dropdown-divider {
      height: 0;
      margin: 0.5rem 0;
      overflow: hidden;
      border-top: 1px solid #e9ecef;
    }

    /* Reset all specific filter-menu z-index */
    .filter-menu,
    .users-table thead th,
    .users-table tbody td,
    .user-row,
    .table-responsive {
      z-index: auto !important;
    }

    /* Make sure form button for logout looks like regular menu item - MATCHING DASHBOARD */
    button.dropdown-item {
      text-align: left;
      background: none;
      border: none;
      width: 100%;
      font-size: 13px; /* MATCHING DASHBOARD */
      padding: 0.5rem 1.5rem;
      cursor: pointer;
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
    }

    button.dropdown-item:hover {
      background-color: #f8f9fa;
    }

    /* Additional fixes to prevent scrolling */
    .min-vh-100 {
      min-height: 100vh !important;
      overflow-x: hidden !important;
    }

    /* Adjust header position based on sidebar state */
    @media (min-width: 992px) {
      .header {
        left: 240px !important;
        width: calc(100% - 240px) !important;
      }

      body.sidebar-hidden .header {
        left: 0 !important;
        width: 100% !important;
      }
    }

    /* Text utility classes - MATCHING DASHBOARD */
    .text-muted {
      font-size: 13px !important; /* MATCHING DASHBOARD */
      font-family: 'Poppins', sans-serif; /* MATCHING FONT */
    }

    /* Sidebar nav titles - MATCHING DASHBOARD STYLE */
    .nav-title {
      color: var(--emerald-light);
      font-size: 0.75rem !important; /* MATCHING DASHBOARD SCALE */
      text-transform: uppercase;
      letter-spacing: 0.05rem;
      font-weight: 600;
      padding: 1rem 1.25rem 0.5rem;
      opacity: 0.8;
      font-family: 'Poppins', sans-serif !important; /* MATCHING FONT */
    }

    /* ========================================
       MINIMALIST MODAL STYLING
       ======================================== */

    /* Minimalist Modal Base */
    .minimalist-modal {
      border: none;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      font-family: 'Poppins', sans-serif;
    }

    /* Modal Header */
    .minimalist-header {
      background: transparent;
      border: none;
      padding: 20px 24px 0 24px;
      position: relative;
    }

    .minimalist-title {
      font-size: 18px;
      font-weight: 600;
      color: #2c3e50;
      margin: 0;
    }

    .minimalist-close {
      position: absolute;
      top: 16px;
      right: 20px;
      background: #f8f9fa;
      border: none;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      opacity: 1;
      font-size: 14px;
    }

    .minimalist-close:hover {
      background: #e9ecef;
      transform: scale(1.05);
    }

    /* Modal Body */
    .minimalist-body {
      padding: 24px;
      background: #ffffff;
    }

    /* Avatar Section */
    .avatar-container {
      position: relative;
      display: inline-block;
      margin-bottom: 16px;
    }

    .user-avatar, .edit-avatar {
      border-radius: 50%;
      border: 3px solid #f8f9fa;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      object-fit: cover;
    }

    .user-name {
      font-size: 20px;
      font-weight: 600;
      color: #2c3e50;
      margin: 12px 0 4px 0;
    }

    .user-email {
      font-size: 14px;
      color: #6c757d;
      margin: 0 0 12px 0;
    }

    .user-role-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .user-role-badge.admin {
      background: #fef2f2;
      color: #dc2626;
    }

    .user-role-badge.user {
      background: #f0f9ff;
      color: #0369a1;
    }

    /* User Details Grid */
    .user-details-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 16px;
      margin-top: 24px;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #f1f5f9;
    }

    .detail-item:last-child {
      border-bottom: none;
    }

    .detail-label {
      font-size: 13px;
      color: #64748b;
      font-weight: 500;
    }

    .detail-value {
      font-size: 13px;
      color: #334155;
      font-weight: 500;
      text-align: right;
    }

    /* Avatar Edit Section */
    .avatar-edit-section {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 32px;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 12px;
    }

    .upload-label {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      color: #475569;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .upload-label:hover {
      background: #f1f5f9;
      border-color: #cbd5e1;
    }

    .form-control-file {
      display: none;
    }

    /* Form Styling */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
      margin-bottom: 32px;
    }

    @media (min-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr 1fr;
      }

      .form-group:first-child,
      .form-group:nth-child(2) {
        grid-column: 1 / -1;
      }
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .minimalist-label {
      font-size: 13px;
      font-weight: 500;
      color: #374151;
      margin-bottom: 6px;
    }

    .minimalist-input,
    .minimalist-select {
      padding: 12px 16px;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      font-size: 14px;
      font-family: 'Poppins', sans-serif;
      transition: all 0.2s ease;
      background: #ffffff;
    }

    .minimalist-input:focus,
    .minimalist-select:focus {
      outline: none;
      border-color: #0F4C3A;
      box-shadow: 0 0 0 3px rgba(15, 76, 58, 0.1);
    }

    /* Password Section */
    .password-section {
      border-top: 1px solid #f1f5f9;
      padding-top: 24px;
    }

    .section-title {
      font-size: 16px;
      font-weight: 600;
      color: #374151;
      margin: 0 0 4px 0;
    }

    .section-subtitle {
      font-size: 13px;
      color: #6b7280;
      margin: 0 0 20px 0;
    }

    /* Modal Footer */
    .minimalist-footer {
      background: #f8f9fa;
      border: none;
      padding: 20px 24px;
      display: flex;
      gap: 12px;
      justify-content: flex-end;
    }

    /* Minimalist Buttons */
    .btn-minimalist {
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      font-family: 'Poppins', sans-serif;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-edit {
      background: #0F4C3A;
      color: white;
    }

    .btn-edit:hover {
      background: #0A3628;
      transform: translateY(-1px);
    }

    .btn-cancel {
      background: transparent;
      color: #6b7280;
      border: 1px solid #e5e7eb;
    }

    .btn-cancel:hover {
      background: #f9fafb;
      color: #374151;
    }

    .btn-save {
      background: #059669;
      color: white;
    }

    .btn-save:hover {
      background: #047857;
      transform: translateY(-1px);
    }

    /* Animation */
    .modal.fade .modal-dialog {
      transform: scale(0.95) translateY(-20px);
      transition: all 0.3s ease;
    }

    .modal.show .modal-dialog {
      transform: scale(1) translateY(0);
    }

    /* File Upload Styling */
    .file-upload-container {
      position: relative;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .file-upload-preview {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px dashed #cbd5e1;
      transition: all 0.2s ease;
    }

    .file-upload-preview:hover {
      border-color: #0F4C3A;
      background: #f0f9f5;
    }

    .file-upload-preview i {
      color: #64748b;
      font-size: 20px;
    }

    .file-upload-info {
      flex: 1;
    }

    .file-upload-title {
      font-size: 14px;
      font-weight: 500;
      color: #374151;
      margin: 0 0 4px 0;
    }

    .file-upload-subtitle {
      font-size: 12px;
      color: #6b7280;
      margin: 0;
    }

    /* Form Validation Feedback */
    .invalid-feedback {
      display: block;
      width: 100%;
      margin-top: 4px;
      font-size: 12px;
      color: #dc3545;
    }

    .is-invalid {
      border-color: #dc3545;
    }

    .is-invalid:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    /* Progress Indicator */
    .form-progress {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 24px;
      padding: 12px 16px;
      background: #f0f9ff;
      border-radius: 8px;
      border-left: 4px solid #0369a1;
    }

    .form-progress-icon {
      color: #0369a1;
      font-size: 16px;
    }

    .form-progress-text {
      font-size: 13px;
      color: #0369a1;
      font-weight: 500;
    }

   /* ========================================
   OPTIMIZED HEIGHT ADJUSTMENTS FOR EDIT MODAL ONLY
   ======================================== */

/* Size-adjusted Edit Profile Modal with Better Height Control */
#editProfileModal .modal-dialog {
  max-width: 480px;
  max-height: 85vh;
  margin: 2vh auto;
}

#editProfileModal .modal-content {
  max-height: 85vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

#editProfileModal .modal-body {
  overflow-y: auto;
  flex: 1;
  max-height: calc(85vh - 140px);
  padding: 14px 18px;
  margin: 0;
}

#editProfileModal .minimalist-header {
  padding: 14px 18px 6px 18px;
  flex-shrink: 0;
}

#editProfileModal .minimalist-footer {
  padding: 10px 18px;
  flex-shrink: 0;
}

#editProfileModal .minimalist-title {
  font-size: 15px;
}

#editProfileModal .minimalist-close {
  top: 10px;
  right: 14px;
  width: 26px;
  height: 26px;
  font-size: 12px;
}

/* Compact Progress Indicator */
#editProfileModal .form-progress {
  padding: 6px 10px;
  margin-bottom: 12px;
  border-left-width: 3px;
}

#editProfileModal .form-progress-icon {
  font-size: 12px;
}

#editProfileModal .form-progress-text {
  font-size: 11px;
}

/* Compact Avatar Section */
#editProfileModal .avatar-edit-section {
  padding: 12px;
  margin-bottom: 14px;
  border-radius: 6px;
  gap: 12px;
}

#editProfileModal .edit-avatar {
  width: 55px;
  height: 55px;
}

#editProfileModal .file-upload-container {
  gap: 10px;
}

#editProfileModal .file-upload-preview {
  width: 35px;
  height: 35px;
}

#editProfileModal .file-upload-preview i {
  font-size: 14px;
}

#editProfileModal .file-upload-title {
  font-size: 12px;
  margin-bottom: 1px;
}

#editProfileModal .file-upload-subtitle {
  font-size: 10px;
}

#editProfileModal .upload-label {
  padding: 5px 10px;
  font-size: 11px;
}

#editProfileModal .upload-label i {
  font-size: 10px;
}

/* Compact Form Fields */
#editProfileModal .form-grid {
  gap: 12px;
  margin-bottom: 14px;
}

#editProfileModal .form-group:first-child,
#editProfileModal .form-group:nth-child(2) {
  grid-column: 1 / -1;
}

#editProfileModal .minimalist-label {
  font-size: 11px;
  margin-bottom: 3px;
}

#editProfileModal .minimalist-label i {
  margin-right: 3px;
  font-size: 10px;
}

#editProfileModal .minimalist-input,
#editProfileModal .minimalist-select {
  padding: 7px 10px;
  font-size: 12px;
  border-radius: 6px;
}

#editProfileModal .form-group {
  margin-bottom: 12px;
}

/* Compact Password Section */
#editProfileModal .password-section {
  padding-top: 12px;
  border-top: 1px solid #f1f5f9;
}

#editProfileModal .section-title {
  font-size: 13px;
  margin: 0 0 2px 0;
}

#editProfileModal .section-title i {
  font-size: 12px;
}

#editProfileModal .section-subtitle {
  font-size: 11px;
  margin-bottom: 10px;
}

/* Compact Buttons */
#editProfileModal .btn-minimalist {
  padding: 7px 14px;
  font-size: 11px;
}

#editProfileModal .btn-minimalist i {
  font-size: 10px;
}

/* Enhanced Responsive Design for Edit Modal */
@media (max-width: 576px) {
  #editProfileModal .modal-dialog {
    max-width: 96% !important;
    margin: 1vh auto;
    max-height: 92vh;
  }

  #editProfileModal .modal-content {
    max-height: 92vh;
  }

  #editProfileModal .modal-body {
    max-height: calc(92vh - 120px);
    padding: 10px 12px !important;
  }

  #editProfileModal .minimalist-header {
    padding: 10px 12px 4px 12px !important;
  }

  #editProfileModal .minimalist-footer {
    padding: 8px 12px !important;
    flex-direction: column;
    gap: 6px;
  }

  #editProfileModal .avatar-edit-section {
    flex-direction: column !important;
    text-align: center;
    gap: 8px !important;
    padding: 8px !important;
    margin-bottom: 10px !important;
  }

  #editProfileModal .form-grid {
    grid-template-columns: 1fr !important;
    gap: 8px !important;
    margin-bottom: 10px !important;
  }

  #editProfileModal .form-group {
    margin-bottom: 8px !important;
  }

  #editProfileModal .password-section {
    padding-top: 8px !important;
  }

  #editProfileModal .form-progress {
    padding: 4px 8px !important;
    margin-bottom: 8px !important;
  }
}

/* Medium height screens optimization */
@media (max-height: 750px) {
  #editProfileModal .modal-dialog {
    max-height: 90vh;
    margin: 1vh auto;
  }

  #editProfileModal .modal-content {
    max-height: 90vh;
  }

  #editProfileModal .modal-body {
    max-height: calc(90vh - 130px);
  }

  #editProfileModal .form-progress {
    padding: 5px 8px !important;
    margin-bottom: 8px !important;
  }

  #editProfileModal .avatar-edit-section {
    padding: 8px !important;
    margin-bottom: 10px !important;
  }

  #editProfileModal .form-grid {
    gap: 8px !important;
    margin-bottom: 10px !important;
  }

  #editProfileModal .form-group {
    margin-bottom: 8px !important;
  }

  #editProfileModal .password-section {
    padding-top: 8px !important;
  }
}

/* Very short screens optimization */
@media (max-height: 600px) {
  #editProfileModal .modal-dialog {
    max-height: 95vh;
    margin: 0.5vh auto;
  }

  #editProfileModal .modal-content {
    max-height: 95vh;
  }

  #editProfileModal .modal-body {
    max-height: calc(95vh - 110px);
    padding: 8px 14px;
  }

  #editProfileModal .minimalist-header {
    padding: 8px 14px 4px 14px !important;
  }

  #editProfileModal .minimalist-footer {
    padding: 6px 14px !important;
  }

  #editProfileModal .edit-avatar {
    width: 45px !important;
    height: 45px !important;
  }

  #editProfileModal .form-progress {
    padding: 3px 6px !important;
    margin-bottom: 6px !important;
    font-size: 10px !important;
  }

  #editProfileModal .avatar-edit-section {
    padding: 6px !important;
    margin-bottom: 8px !important;
  }

  #editProfileModal .form-grid {
    gap: 6px !important;
    margin-bottom: 8px !important;
  }

  #editProfileModal .form-group {
    margin-bottom: 6px !important;
  }

  #editProfileModal .minimalist-input,
  #editProfileModal .minimalist-select {
    padding: 5px 8px !important;
    font-size: 11px !important;
  }

  #editProfileModal .minimalist-label {
    font-size: 10px !important;
    margin-bottom: 2px !important;
  }
}

/* Custom scrollbar for modal body */
#editProfileModal .modal-body::-webkit-scrollbar {
  width: 4px;
}

#editProfileModal .modal-body::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 2px;
}

#editProfileModal .modal-body::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 2px;
}

#editProfileModal .modal-body::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Ensure proper spacing between sections */
#editProfileModal .password-section .form-group:last-child {
  margin-bottom: 0 !important;
}

/* Optimize for landscape mobile */
@media (max-width: 768px) and (max-height: 500px) and (orientation: landscape) {
  #editProfileModal .modal-dialog {
    max-height: 95vh;
    margin: 0.5vh auto;
  }

  #editProfileModal .modal-body {
    max-height: calc(95vh - 100px);
  }

  #editProfileModal .avatar-edit-section {
    flex-direction: row !important;
    padding: 6px !important;
  }

  #editProfileModal .edit-avatar {
    width: 40px !important;
    height: 40px !important;
  }
}

    /* Responsive Design for Edit Modal */
    @media (max-width: 576px) {
      #editProfileModal .modal-dialog {
        max-width: 95% !important;
        margin: 1rem auto;
      }

      #editProfileModal .minimalist-body {
        padding: 12px 16px !important;
      }

      #editProfileModal .minimalist-header {
        padding: 12px 16px 6px 16px !important;
      }

      #editProfileModal .minimalist-footer {
        padding: 10px 16px !important;
        flex-direction: column;
        gap: 8px;
      }

      #editProfileModal .avatar-edit-section {
        flex-direction: column !important;
        text-align: center;
        gap: 12px !important;
      }

      #editProfileModal .form-grid {
        grid-template-columns: 1fr !important;
      }
    }

    /* Focus States */
    .btn-minimalist:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(15, 76, 58, 0.2);
    }

    /* Loading State */
    .btn-minimalist:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    /* Success/Error States */
    .form-group.error .minimalist-input,
    .form-group.error .minimalist-select {
      border-color: #ef4444;
    }

    .form-group.success .minimalist-input,
    .form-group.success .minimalist-select {
      border-color: #10b981;
    }

    /* General responsive adjustments */
    @media (max-width: 576px) {
      .minimalist-body {
        padding: 16px;
      }

      .minimalist-header {
        padding: 16px 16px 0 16px;
      }

      .minimalist-footer {
        padding: 16px;
        flex-direction: column;
      }

      .avatar-edit-section {
        flex-direction: column;
        text-align: center;
      }

      .user-details-grid {
        gap: 12px;
      }
    }
  </style>
</head>

@if (!request()->routeIs('login') && !request()->routeIs('home') && !request()->routeIs('register') && !request()->routeIs('password.request') && !request()->routeIs('password.update') && !request()->routeIs('password.reset') && !request()->routeIs('2fa.verify.form'))
    @php
    $user = Auth::user();
    @endphp

    @if (!request()->routeIs('profile') && !request()->routeIs('profile.edit'))
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
        @include('partials.menu') <!-- Loads sidebar menu content -->
    </div>

    <!-- Backdrop overlay for small screen sidebar -->
    <div class="sidebar-backdrop" id="sidebar-backdrop" onclick="toggleSidebar()"></div>
    @endif

    <!-- Main Page Wrapper -->
    <div class="wrapper d-flex flex-column min-vh-100">

        <!-- Header/Navbar -->
        <header class="header header-sticky shadow-sm border-bottom" id="header">
            <div class="container-fluid d-flex align-items-center justify-content-between" style="height: 56px;">
                <!-- Left side with toggle button -->
                <button class="header-toggler" type="button" onclick="toggleSidebar()" style="margin: 0; padding-left: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" viewBox="0 0 24 24" fill="black">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="black" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>

                @if ($user->role === 'user' && (!request()->routeIs('profile') && !request()->routeIs('profile.edit') && !request()->routeIs('user.dashboard')))
                <a href="{{ route('chat.new') }}"
                   class="btn btn-outline-primary d-none d-lg-inline-flex align-items-center gap-2 rounded-pill shadow-sm px-3 py-2"
                   style="transition: background-color 0.2s ease, box-shadow 0.2s ease;"
                   data-bs-toggle="tooltip" data-bs-placement="bottom" title="Start a new conversation">
                    <i class="fa-solid fa-comment-dots"></i>
                    <span class="fw-medium">New Chat</span>
                </a>
                @endif

                <!-- Right side with avatar dropdown -->
                <div class="ms-auto d-flex align-items-center" style="z-index: 2000; gap: 1rem; padding-right: 1rem;">
                    <!-- User dropdown menu -->
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle d-flex align-items-center text-decoration-none"
                           id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 class="rounded-circle border"
                                 width="36" height="36"
                                 alt="{{ $user->email }}">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                                    <i class="fa-solid fa-user"></i>
                                    Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
    @endif

    <!-- Main Page Content -->
    @php
    $route = Route::currentRouteName();
    $containerClass = in_array($route, ['login', 'register']) ? 'container-real' : 'container';
    @endphp

    <div class="{{ $containerClass }}" style="position: relative; z-index: 1;">
        @yield('content')
    </div>

    <!-- Profile Modal - Minimalist Design -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content minimalist-modal">
                <!-- Simplified Header with No Background -->
                <div class="modal-header minimalist-header">
                    <button type="button" class="btn-close minimalist-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body minimalist-body">
                    <!-- User Avatar Section -->
                    <div class="text-center mb-4">
                        <div class="avatar-container">
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 class="user-avatar"
                                 width="80" height="80"
                                 alt="{{ $user->email }}">
                        </div>
                        <h4 class="user-name">{{ $user->name }}</h4>
                        <p class="user-email">{{ $user->email }}</p>
                        <span class="user-role-badge {{ $user->role === 'admin' ? 'admin' : 'user' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <!-- User Details Grid -->
                    <div class="user-details-grid">
                        <div class="detail-item">
                            <span class="detail-label">User ID</span>
                            <span class="detail-value">{{ $user->userID }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Member Since</span>
                            <span class="detail-value">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>

                        @if ($user->role === 'user')
                        <div class="detail-item">
                            <span class="detail-label">Year Level</span>
                            <span class="detail-value">{{ $user->year ? $user->year->year_level : 'Not set' }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Course</span>
                            <span class="detail-value">{{ $user->course ? $user->course->course_name : 'Not set' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Clean Footer -->
                <div class="modal-footer minimalist-footer">
                    <button type="button" class="btn-minimalist btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-pencil"></i>
                        Edit Profile
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal - Size Adjusted Minimalist Design -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content minimalist-modal">
                <!-- Compact Header -->
                <div class="modal-header minimalist-header">
                    <h5 class="modal-title minimalist-title">Edit Profile</h5>
                    <button type="button" class="btn-close minimalist-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body minimalist-body">
                    <!-- Compact Progress Indicator -->
                    <div class="form-progress">
                        <i class="fa-solid fa-info-circle form-progress-icon"></i>
                        <span class="form-progress-text">Update your profile information and preferences</span>
                    </div>

                    <form id="editProfileForm" action="{{ route('profile.update', $user->userID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Compact Avatar Section -->
                        <div class="avatar-edit-section">
                            <div class="current-avatar">
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                    alt="User Avatar" class="edit-avatar" id="avatarPreview">
                            </div>
                            <div class="avatar-upload">
                                <div class="file-upload-container">
                                    <div class="file-upload-preview">
                                        <i class="fa-solid fa-camera"></i>
                                    </div>
                                    <div class="file-upload-info">
                                        <p class="file-upload-title">Change Profile Photo</p>
                                        <p class="file-upload-subtitle">JPG, PNG or GIF (max. 2MB)</p>
                                    </div>
                                </div>
                                <label for="avatar" class="upload-label">
                                    <i class="fa-solid fa-upload"></i>
                                    Choose File
                                </label>
                                <input type="file" name="avatar" id="avatar" class="form-control-file" accept="image/*">
                            </div>
                        </div>

                        <!-- Compact Form Fields -->
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="minimalist-label">
                                    <i class="fa-solid fa-user"></i>
                                    Full Name
                                </label>
                                <input type="text" name="name" id="name" class="minimalist-input" value="{{ $user->name }}" required>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="minimalist-label">
                                    <i class="fa-solid fa-envelope"></i>
                                    Email Address
                                </label>
                                <input type="email" name="email" id="email" class="minimalist-input" value="{{ $user->email }}" required>
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>

                            @if ($user->role === 'user')
                            <div class="form-group">
                                <label for="year" class="minimalist-label">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    Year Level
                                </label>
                                <select name="year_id" id="year" class="minimalist-select">
                                    <option value="">Select Year Level</option>
                                    @foreach ($years ?? [] as $year)
                                        <option value="{{ $year->yearID }}" {{ $user->yearID == $year->yearID ? 'selected' : '' }}>
                                            {{ $year->year_level }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="year-error"></div>
                            </div>

                            <div class="form-group">
                                <label for="course" class="minimalist-label">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    Course
                                </label>
                                <select name="course_id" id="course" class="minimalist-select">
                                    <option value="">Select Course</option>
                                    @foreach ($courses ?? [] as $course)
                                        <option value="{{ $course->courseID }}" {{ $user->courseID == $course->courseID ? 'selected' : '' }}>
                                            {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="course-error"></div>
                            </div>
                            @endif
                        </div>

                        <!-- Compact Password Section -->
                        <div class="password-section">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fa-solid fa-shield-halved"></i>
                                <h6 class="section-title">Security Settings</h6>
                            </div>
                            <p class="section-subtitle">Update your password to keep your account secure. Leave blank to keep current password.</p>

                            <div class="form-group">
                                <label for="old_password" class="minimalist-label">
                                    <i class="fa-solid fa-key"></i>
                                    Current Password
                                </label>
                                <input type="password" name="old_password" id="old_password" class="minimalist-input" placeholder="Enter your current password">
                                <div class="invalid-feedback" id="old-password-error"></div>
                            </div>

                            <div class="form-group">
                                <label for="new_password" class="minimalist-label">
                                    <i class="fa-solid fa-lock"></i>
                                    New Password
                                </label>
                                <input type="password" name="new_password" id="new_password" class="minimalist-input" placeholder="Enter new password">
                                <div class="invalid-feedback" id="new-password-error"></div>
                            </div>

                            <div class="form-group">
                                <label for="new_password_confirmation" class="minimalist-label">
                                    <i class="fa-solid fa-lock-open"></i>
                                    Confirm New Password
                                </label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="minimalist-input" placeholder="Confirm new password">
                                <div class="invalid-feedback" id="confirm-password-error"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Compact Action Footer -->
                <div class="modal-footer minimalist-footer">
                    <button type="button" class="btn-minimalist btn-cancel" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn-minimalist btn-save" onclick="submitProfileForm()">
                        <i class="fa-solid fa-check"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CoreUI Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/js/coreui.bundle.min.js"
            crossorigin="anonymous"></script>

    <!-- Enhanced Profile Modal Scripts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Avatar preview functionality
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');

        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Form validation
        function validateForm() {
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            // Name validation
            const name = document.getElementById('name');
            if (!name.value.trim()) {
                name.classList.add('is-invalid');
                document.getElementById('name-error').textContent = 'Name is required';
                isValid = false;
            }

            // Email validation
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim()) {
                email.classList.add('is-invalid');
                document.getElementById('email-error').textContent = 'Email is required';
                isValid = false;
            } else if (!emailRegex.test(email.value)) {
                email.classList.add('is-invalid');
                document.getElementById('email-error').textContent = 'Please enter a valid email address';
                isValid = false;
            }

            // Password validation
            const oldPassword = document.getElementById('old_password');
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('new_password_confirmation');

            if (newPassword.value || confirmPassword.value || oldPassword.value) {
                if (!oldPassword.value) {
                    oldPassword.classList.add('is-invalid');
                    document.getElementById('old-password-error').textContent = 'Current password is required when changing password';
                    isValid = false;
                }

                if (!newPassword.value) {
                    newPassword.classList.add('is-invalid');
                    document.getElementById('new-password-error').textContent = 'New password is required';
                    isValid = false;
                } else if (newPassword.value.length < 8) {
                    newPassword.classList.add('is-invalid');
                    document.getElementById('new-password-error').textContent = 'Password must be at least 8 characters long';
                    isValid = false;
                }

                if (newPassword.value !== confirmPassword.value) {
                    confirmPassword.classList.add('is-invalid');
                    document.getElementById('confirm-password-error').textContent = 'Passwords do not match';
                    isValid = false;
                }
            }

            return isValid;
        }

        // Global function for form submission
        window.submitProfileForm = function() {
            if (validateForm()) {
                const saveBtn = document.querySelector('.btn-save');
                const originalText = saveBtn.innerHTML;

                // Show loading state
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

                // Submit form
                document.getElementById('editProfileForm').submit();
            }
        };

        // Real-time validation
        document.getElementById('name').addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
                document.getElementById('name-error').textContent = 'Name is required';
            } else {
                this.classList.remove('is-invalid');
                document.getElementById('name-error').textContent = '';
            }
        });

        document.getElementById('email').addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
                document.getElementById('email-error').textContent = 'Email is required';
            } else if (!emailRegex.test(this.value)) {
                this.classList.add('is-invalid');
                document.getElementById('email-error').textContent = 'Please enter a valid email address';
            } else {
                this.classList.remove('is-invalid');
                document.getElementById('email-error').textContent = '';
            }
        });

        // Detect if we're on the profile page and close the sidebar
        if (window.location.href.includes("user/profile")) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const header = document.getElementById('header');

            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
            if (header) {
                header.style.left = '0';
                header.style.width = '100%';
            }
        }

        // Initialize Bootstrap tooltips and dropdowns
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl)
        });

        // Handle success messages with SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}'
            });
        @endif

        // Handle validation errors for the profile form
        @if($errors->any())
            if ({{ $errors->has('name') || $errors->has('email') || $errors->has('avatar') ||
                  $errors->has('old_password') || $errors->has('new_password') ? 'true' : 'false' }}) {
                var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
                editProfileModal.show();

                // Display validation errors
                @foreach($errors->all() as $error)
                    console.error('Validation error: {{ $error }}');
                @endforeach
            }
        @endif
    });

    // Sidebar Toggle Function
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const header = document.getElementById('header');
        const isLarge = window.innerWidth >= 992;

        if (isLarge) {
            document.body.classList.toggle('sidebar-hidden');
            if (document.body.classList.contains('sidebar-hidden')) {
                header.style.left = '0';
                header.style.width = '100%';
            } else {
                header.style.left = '240px';
                header.style.width = 'calc(100% - 240px)';
            }
        } else {
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }
    }

    // Handle sidebar close button
    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.querySelector('.sidebar .btn-close');
        if (closeBtn) {
            closeBtn.onclick = function(e) {
                e.preventDefault();
                toggleSidebar();
            };
        }

        const header = document.querySelector('.header');
        if (header) {
            header.style.zIndex = '1500';
            header.style.position = 'fixed';
            header.style.top = '0';

            if (window.innerWidth >= 992 && !document.body.classList.contains('sidebar-hidden')) {
                header.style.left = '240px';
                header.style.width = 'calc(100% - 240px)';
            } else {
                header.style.left = '0';
                header.style.width = '100%';
            }
        }
    });

    // Automatically hide mobile sidebar on resize to desktop
    window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const header = document.querySelector('.header');

        if (window.innerWidth >= 992) {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');

            if (header) {
                if (document.body.classList.contains('sidebar-hidden')) {
                    header.style.left = '0';
                    header.style.width = '100%';
                } else {
                    header.style.left = '240px';
                    header.style.width = 'calc(100% - 240px)';
                }
            }
        } else {
            if (header) {
                header.style.left = '0';
                header.style.width = '100%';
            }
        }
    });
    </script>

</body>

</html>
