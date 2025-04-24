@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Frontend Features Table</h2>
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Feature</th>
          <th>Description</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>User Login</td>
          <td>Allow users to log in with email and password</td>
          <td><span class="badge bg-warning text-dark">In Progress</span></td>
        </tr>
        <tr>
          <td>2</td>
          <td>Dashboard</td>
          <td>Main interface showing user-specific data</td>
          <td><span class="badge bg-success">Completed</span></td>
        </tr>
        <tr>
          <td>3</td>
          <td>Chat Interface</td>
          <td>Real-time messaging UI for chatbot</td>
          <td><span class="badge bg-secondary">Pending</span></td>
        </tr>
      </tbody>
    </table>
  </div>


 @endsection
