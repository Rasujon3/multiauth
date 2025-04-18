<h2>Admin Forget Password</h2>

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

<form action="{{ route('admin_forget_password_submit') }}" method="post">
    @csrf
    <table>
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" placeholder="Email"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Submit</button>
                <div> <a href="{{ route('admin_login') }}">Back to login page</a> </div>
            </td>
        </tr>
    </table>
</form>
