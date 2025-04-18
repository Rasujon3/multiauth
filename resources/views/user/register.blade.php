<a href="{{ route('home') }}">Home</a> | <a href="{{ route('about') }}">About</a> |
<a href="{{ route('login') }}">Login</a> | <a href="{{ route('register') }}">Register</a>

<h2>User Registration</h2>

@if($errors->any())
    @foreach($errors->all() as $error)
        {{ $error }} <br>
    @endforeach
@endif

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif
@if(session('error'))
    <p style="color: red">{{ session('error') }}</p>
@endif

<form action="{{ route('register_submit') }}" method="post">
    @csrf
    <table>
        <tr>
            <td>Name</td>
            <td><input type="text" name="name" placeholder="Name"></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" placeholder="Email"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" placeholder="Password"></td>
        </tr>
        <tr>
            <td>Confirm Password</td>
            <td><input type="password" name="confirm_password" placeholder="Confirm Password"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Submit</button>
            </td>
        </tr>
    </table>
</form>

