<h2>Admin Login</h2>

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

<form action="{{ route('admin_login_submit') }}" method="post">
    @csrf
    <table>
        <tr>
            <td>Username</td>
            <td><input type="text" name="email" placeholder="Email"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" placeholder="Password"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Login"></td>
            <div> <a href="{{ route('admin_forget_password') }}">Forget Password ?</a> </div>
        </tr>
    </table>
</form>
