<h2>User Dashboard</h2>
<h3>Welcome, {{ Auth::guard('web')->user()->name }}</h3>

<a href="{{ route('logout') }}">Logout</a>
