<h2>Admin Dashboard</h2>
<h3>Welcome, {{ Auth::guard('admin')->user()->name }}</h3>

<a href="{{ route('admin_logout') }}">Logout</a>
