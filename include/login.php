<!-- not open for user-->
<!--<h2>Login to Website</h2>-->
<!-- open for user -->
<h2>Admin login</h2>
<form method="post" action="processLogin.php" class="form">
    <fieldset>
        <label for="inputEmail" class="sr-only">UserID</label>
        <input type="text" id="inputUser" name="inputUser"
               placeholder="Enter User ID"
               required=""
               autofocus="">
        <br/>
        <label for="inputPassword">Password</label>
        <input type="password" id="inputPassword" name="inputPassword"
               placeholder="Password"
               required="">
        <br/>
        <button type="submit" name="login-submit"
                id="login-submit">Sign in
        </button>
    </fieldset>
    <fieldset>      <?php
        if ($error == 'true') {
            echo "<div style='color: red'>Invalid email or password. Please try again.</div>";
        }
        ?></fieldset>
</form>