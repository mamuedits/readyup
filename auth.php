<?php
require_once 'config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        handleSignup();
    } elseif (isset($_POST['check'])) {
        handleVerify();
    } elseif (isset($_POST['login'])) {
        handleLogin();
    } elseif (isset($_POST['check-email'])) {
        handleForgotPassword();
    } elseif (isset($_POST['check-reset-otp'])) {
        handleResetCode();
    } elseif (isset($_POST['change-password'])) {
        handleNewPassword();
    } elseif (isset($_POST['login-now'])) {
        header('Location: auth.php?page=login');
        exit();
    }
}

// Handle different pages
$page = isset($_GET['page']) ? $_GET['page'] : 'login';
renderPage($page);

// Function to render the appropriate page
function renderPage($page) {
    switch ($page) {
        case 'signup':
            renderSignup();
            break;
        case 'verify':
            renderVerifyOTP();
            break;
        case 'forgot':
            renderForgotPassword();
            break;
        case 'reset-code':
            renderResetCode();
            break;
        case 'new-password':
            renderNewPassword();
            break;
        case 'password-changed':
            renderPasswordChanged();
            break;
        case 'logout':
            handleLogout();
            break;
        default:
            renderLogin();
    }
}

/* ====================== */
/* === CONTROLLER LOGIC === */
/* ====================== */

function handleSignup() {
    global $con, $errors, $name, $email;

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    // Check if it starts with a letter or underscore
    if (!preg_match('/^[A-Za-z_]/', $name)) {
        $errors['name']= "Name must start with a letter or underscore.";
    }

    // Check if it contains at least one letter
    if (!preg_match('/[A-Za-z]/', $name)) {
        $errors['name'] = "Name must contain at least one alphabet.";
    }

    // Email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format!";
    }

    // Password match check
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    }

    // Password strength validation
    if (
        !preg_match('/[A-Z]/', $password) ||    // at least one uppercase
        !preg_match('/[a-z]/', $password) ||    // at least one lowercase
        !preg_match('/[0-9]/', $password) ||    // at least one number
        !preg_match('/[\W_]/', $password) ||    // at least one special character
        strlen($password) < 8                   // minimum 8 characters
    ) {
        $errors['password_strength'] = "Password must be at least 8 characters long and include a capital letter, a small letter, a number, and a special character.";
    }

    // Check if email already exists
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if (mysqli_num_rows($res) > 0) {
        $errors['email_exists'] = "Email already exists!";
    }

    // Proceed if no errors
    if (count($errors) === 0) {
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";

        $insert_data = "INSERT INTO usertable (name, email, password, code, status) 
                        VALUES('$name', '$email', '$encpass', '$code', '$status')";

        if (mysqli_query($con, $insert_data)) {
            $subject = "Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From: mamuedits@gmail.com";

            if (mail($email, $subject, $message, $sender)) {
                $_SESSION['info'] = "We've sent a verification code to your email - $email";
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('Location: auth.php?page=verify');
                exit();
            } else {
                $errors['otp-error'] = "Failed to send code!";
            }
        } else {
            $errors['db-error'] = "Failed to insert data!";
        }
    }
}


function handleVerify() {
    global $con, $errors;
    
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        
        $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
        
        if (mysqli_query($con, $update_otp)) {
            $_SESSION['name'] = $fetch_data['name'];
            $_SESSION['email'] = $email;
            header('Location: auth.php?page=home');
            exit();
        } else {
            $errors['otp-error'] = "Failed to update code!";
        }
    } else {
        $errors['otp-error'] = "Incorrect code!";
    }
}

function handleLogin() {
    global $con, $errors, $email;
    
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    
    if (mysqli_num_rows($res) > 0) {
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        
        if (password_verify($password, $fetch_pass)) {
            $_SESSION['email'] = $email;
            $status = $fetch['status'];
            
            if ($status == 'verified') {
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('Location: index1.php');
                exit();
            } else {
                $_SESSION['info'] = "Please verify your email - $email";
                header('Location: auth.php?page=verify');
                exit();
            }
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "You're not yet a member!";
    }
}

function handleForgotPassword() {
    global $con, $errors, $email;
    
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM usertable WHERE email='$email'";
    $run_sql = mysqli_query($con, $check_email);
    
    if (mysqli_num_rows($run_sql) > 0) {
        $code = rand(999999, 111111);
        $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
        
        if (mysqli_query($con, $insert_code)) {
            $subject = "Password Reset Code";
            $message = "Your password reset code is $code";
            $sender = "From: mamuedits@gmail.com";
            
            if (mail($email, $subject, $message, $sender)) {
                $_SESSION['info'] = "We've sent a password reset OTP to your email - $email";
                $_SESSION['email'] = $email;
                header('Location: auth.php?page=reset-code');
                exit();
            } else {
                $errors['otp-error'] = "Failed to send code!";
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['email'] = "This email doesn't exist!";
    }
}

function handleResetCode() {
    global $con, $errors;
    
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $_SESSION['info'] = "Please create a new password";
        header('Location: auth.php?page=new-password');
        exit();
    } else {
        $errors['otp-error'] = "Incorrect code!";
    }
}

function handleNewPassword() {
    global $con, $errors;

    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    // Password match check
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    }

    // Password strength validation
    if (
        !preg_match('/[A-Z]/', $password) ||    // at least one uppercase
        !preg_match('/[a-z]/', $password) ||    // at least one lowercase
        !preg_match('/[0-9]/', $password) ||    // at least one number
        !preg_match('/[\W_]/', $password) ||    // at least one special character
        strlen($password) < 8                   // minimum 8 characters
    ) {
        $errors['password_strength'] = "Password must be at least 8 characters long and include a capital letter, a small letter, a number, and a special character.";
    }

    if (count($errors) === 0) {
        $code = 0;
        $email = $_SESSION['email'];
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";

        if (mysqli_query($con, $update_pass)) {
            $_SESSION['info'] = "Password changed. Now login with your new password.";
            header('Location: auth.php?page=password-changed');
            exit();
        } else {
            $errors['db-error'] = "Failed to change password!";
        }
    }
}


function handleLogout() {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

/* ====================== */
/* === VIEW TEMPLATES === */
/* ====================== */

function renderLogin() {
    global $errors, $email;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login | ReadyUp</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .gradient-text {
                background: linear-gradient(90deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .auth-section {
                background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('images/back2.png');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
            }
            .auth-card {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .input-field {
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
                transition: all 0.3s ease;
            }
            .input-field:focus {
                background: rgba(255, 255, 255, 0.2);
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            }
            .input-field::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }
            html {
                scroll-behavior: smooth;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Header -->
        <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center">
                        <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                        <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                    </a>
                </div>
                
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><a href="index.php" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                        <li><a href="about.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                        <li><a href="#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Sectors</a></li>
                        <li><a href="contact.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
                    </ul>
                </nav>
                
                <div class="flex items-center space-x-4">
                    <a href="auth.php?page=signup" class="bg-[#276EF1] hover:bg-[#1F5A89] text-white font-semibold py-2 px-6 rounded-full transition duration-300">
                        Sign Up
                    </a>
                    <button class="md:hidden text-white text-2xl" id="mobileMenuButton">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Mobile Menu (hidden by default) -->
        <div class="md:hidden hidden bg-[#121823] py-4 px-4" id="mobileMenu">
            <ul class="space-y-4">
                <li><a href="index.php" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
                <li><a href="about.html" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
                <li><a href="#sectors" class="block text-white hover:text-blue-300 transition duration-300">Sectors</a></li>
                <li><a href="contact.html" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
            </ul>
        </div>

        <!-- Login Section -->
        <section class="auth-section flex items-center justify-center">
            <div class="container mx-auto px-4 py-20">
                <div class="max-w-md mx-auto auth-card p-8">
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
                        <p class="text-gray-300">Sign in to access your account</p>
                    </div>

                    <?php if(count($errors) > 0): ?>
                        <div class="bg-red-500/20 border-l-4 border-red-500 text-red-100 p-4 mb-6 rounded" role="alert">
                            <?php foreach($errors as $showerror): ?>
                                <p><?php echo $showerror; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="email">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="your@email.com" required
                                   value="<?php echo $email ?>" 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-gray-300 text-sm font-medium" for="password">Password</label>
                                <a href="auth.php?page=forgot" class="text-sm text-blue-400 hover:text-blue-300 hover:underline">Forgot password?</a>
                            </div>
                            <input type="password" name="password" id="password" placeholder="••••••••" required
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>

                        <button type="submit" name="login" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                            Login to your account
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-gray-400">
                            Don't have an account? 
                            <a href="auth.php?page=signup" class="text-blue-400 hover:text-blue-300 hover:underline font-medium">Sign up</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <script>
            // Mobile menu toggle functionality
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobileMenuButton');
                const mobileMenu = document.getElementById('mobileMenu');
                
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            });
        </script>
    </body>
    </html>
    <?php
}

function renderSignup() {
    global $errors, $name, $email;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up | ReadyUp</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .gradient-text {
                background: linear-gradient(90deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .auth-section {
                background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('images/back2.png');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
            }
            .auth-card {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .input-field {
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
                transition: all 0.3s ease;
            }
            .input-field:focus {
                background: rgba(255, 255, 255, 0.2);
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            }
            .input-field::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }
            html {
                scroll-behavior: smooth;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Header -->
        <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center">
                        <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                        <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                    </a>
                </div>
                
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><a href="index.php" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                        <li><a href="about.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                        <li><a href="#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Sectors</a></li>
                        <li><a href="contact.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
                    </ul>
                </nav>
                
                <div class="flex items-center space-x-4">
                    <a href="auth.php?page=login" class="bg-[#276EF1] hover:bg-[#1F5A89] text-white font-semibold py-2 px-6 rounded-full transition duration-300">
                        Login
                    </a>
                    <button class="md:hidden text-white text-2xl" id="mobileMenuButton">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Mobile Menu (hidden by default) -->
        <div class="md:hidden hidden bg-[#121823] py-4 px-4" id="mobileMenu">
            <ul class="space-y-4">
                <li><a href="index.php" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
                <li><a href="about.html" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
                <li><a href="#sectors" class="block text-white hover:text-blue-300 transition duration-300">Sectors</a></li>
                <li><a href="contact.html" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
            </ul>
        </div>

        <!-- Signup Section -->
        <section class="auth-section flex items-center justify-center">
            <div class="container mx-auto px-4 py-20">
                <div class="max-w-md mx-auto auth-card p-8">
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-2">Create Account</h2>
                        <p class="text-gray-300">Join ReadyUp to manage your startup events</p>
                    </div>

                    <?php if(count($errors) > 0): ?>
                        <div class="bg-red-500/20 border-l-4 border-red-500 text-red-100 p-4 mb-6 rounded" role="alert">
                            <?php foreach($errors as $showerror): ?>
                                <p><?php echo $showerror; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="name">Full Name</label>
                            <input type="text" name="name" id="name" placeholder="John Doe" required
                                   value="<?php echo $name ?>" 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="email">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="your@email.com" required
                                   value="<?php echo $email ?>" 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="password">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" required
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="cpassword">Confirm Password</label>
                            <input type="password" name="cpassword" id="cpassword" placeholder="••••••••" required
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>

                        <button type="submit" name="signup" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                            Create Account
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-gray-400">
                            Already have an account? 
                            <a href="auth.php?page=login" class="text-blue-400 hover:text-blue-300 hover:underline font-medium">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <script>
            // Mobile menu toggle functionality
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobileMenuButton');
                const mobileMenu = document.getElementById('mobileMenu');
                
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            });
        </script>
    </body>
    </html>
    <?php
}
function renderVerifyOTP() {
    global $errors;
    if (!isset($_SESSION['email'])) {
        header('Location: auth.php?page=login');
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Code Verification | ReadyUp</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .gradient-text {
                background: linear-gradient(90deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .auth-section {
                background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('images/back2.png');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
            }
            .auth-card {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .input-field {
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
                transition: all 0.3s ease;
            }
            .input-field:focus {
                background: rgba(255, 255, 255, 0.2);
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            }
            .input-field::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Header -->
        <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center">
                        <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                        <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                    </a>
                </div>

                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><a href="index.php" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                        <li><a href="about.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                        <li><a href="#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Sectors</a></li>
                        <li><a href="contact.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
                    </ul>
                </nav>

                <div class="flex items-center space-x-4">
                    <a href="auth.php?page=signup" class="bg-[#276EF1] hover:bg-[#1F5A89] text-white font-semibold py-2 px-6 rounded-full transition duration-300">
                        Sign Up
                    </a>
                    <button class="md:hidden text-white text-2xl" id="mobileMenuButton">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>

        <div class="md:hidden hidden bg-[#121823] py-4 px-4" id="mobileMenu">
            <ul class="space-y-4">
                <li><a href="index.php" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
                <li><a href="about.html" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
                <li><a href="#sectors" class="block text-white hover:text-blue-300 transition duration-300">Sectors</a></li>
                <li><a href="contact.html" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
            </ul>
        </div>

        <!-- Verification Section -->
        <section class="auth-section flex items-center justify-center">
            <div class="container mx-auto px-4 py-20">
                <div class="max-w-md mx-auto auth-card p-8">
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.24 7.76a6 6 0 01-8.48 8.48m0-8.48a6 6 0 018.48 8.48" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-2">Verify OTP</h2>
                        <p class="text-gray-300">Enter the verification code sent to your email</p>
                    </div>

                    <?php if (isset($_SESSION['info'])): ?>
                        <div class="bg-green-500/20 border-l-4 border-green-500 text-green-100 p-4 mb-6 rounded" role="alert">
                            <p><?php echo $_SESSION['info']; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (count($errors) > 0): ?>
                        <div class="bg-red-500/20 border-l-4 border-red-500 text-red-100 p-4 mb-6 rounded" role="alert">
                            <?php foreach($errors as $showerror): ?>
                                <p><?php echo $showerror; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" autocomplete="off" class="space-y-6">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="otp">Verification Code</label>
                            <input class="input-field w-full px-4 py-3 rounded-lg focus:outline-none" type="number" name="otp" id="otp" placeholder="Enter OTP" required>
                        </div>

                        <button type="submit" name="check"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <script>
            // Mobile menu toggle functionality
            document.addEventListener('DOMContentLoaded', function () {
                const mobileMenuButton = document.getElementById('mobileMenuButton');
                const mobileMenu = document.getElementById('mobileMenu');

                mobileMenuButton.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            });
        </script>
    </body>
    </html>
    <?php
}

function renderForgotPassword() {
    global $errors, $email;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Forgot Password | ReadyUp</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .gradient-text {
                background: linear-gradient(90deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .auth-section {
                background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('images/back2.png');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
            }
            .auth-card {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .input-field {
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
                transition: all 0.3s ease;
            }
            .input-field:focus {
                background: rgba(255, 255, 255, 0.2);
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            }
            .input-field::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }
        </style>
    </head>
    <body class="font-sans antialiased">

        <!-- Header -->
        <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="index.php" class="flex items-center">
                    <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                    <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                </a>
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><a href="index.php" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                        <li><a href="about.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                        <li><a href="#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Sectors</a></li>
                        <li><a href="contact.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
                    </ul>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="auth.php?page=signup" class="bg-[#276EF1] hover:bg-[#1F5A89] text-white font-semibold py-2 px-6 rounded-full transition duration-300">
                        Sign Up
                    </a>
                    <button class="md:hidden text-white text-2xl" id="mobileMenuButton"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </header>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-[#121823] py-4 px-4" id="mobileMenu">
            <ul class="space-y-4">
                <li><a href="index.php" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
                <li><a href="about.html" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
                <li><a href="#sectors" class="block text-white hover:text-blue-300 transition duration-300">Sectors</a></li>
                <li><a href="contact.html" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
            </ul>
        </div>

        <!-- Forgot Password Section -->
        <section class="auth-section flex items-center justify-center">
            <div class="container mx-auto px-4 py-20">
                <div class="max-w-md mx-auto auth-card p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-white mb-2">Forgot Password</h2>
                        <p class="text-gray-300">Enter your email address to reset your password</p>
                    </div>

                    <?php if(count($errors) > 0): ?>
                        <div class="bg-red-500/20 border-l-4 border-red-500 text-red-100 p-4 mb-6 rounded" role="alert">
                            <?php foreach($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" autocomplete="" class="space-y-6">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2" for="email">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="your@email.com" required
                                   value="<?php echo $email ?>" 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>

                        <button type="submit" name="check-email" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                            Continue
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobileMenuButton');
                const mobileMenu = document.getElementById('mobileMenu');
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            });
        </script>
    </body>
    </html>
    <?php
}

function renderResetCode() {
    global $errors;

    if (!isset($_SESSION['email'])) {
        header('Location: auth.php?page=login');
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Verify Reset Code | ReadyUp</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .gradient-text {
                background: linear-gradient(90deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .auth-section {
                background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('images/back2.png');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
            }
            .auth-card {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .input-field {
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
                transition: all 0.3s ease;
            }
            .input-field:focus {
                background: rgba(255, 255, 255, 0.2);
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            }
            .input-field::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }
        </style>
    </head>
    <body class="font-sans antialiased">

        <!-- Header -->
        <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="index.php" class="flex items-center">
                    <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                    <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                </a>
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><a href="index.php" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                        <li><a href="about.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                        <li><a href="#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Sectors</a></li>
                        <li><a href="contact.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
                    </ul>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="auth.php?page=signup" class="bg-[#276EF1] hover:bg-[#1F5A89] text-white font-semibold py-2 px-6 rounded-full transition duration-300">
                        Sign Up
                    </a>
                    <button class="md:hidden text-white text-2xl" id="mobileMenuButton"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </header>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-[#121823] py-4 px-4" id="mobileMenu">
            <ul class="space-y-4">
                <li><a href="index.php" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
                <li><a href="about.html" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
                <li><a href="#sectors" class="block text-white hover:text-blue-300 transition duration-300">Sectors</a></li>
                <li><a href="contact.html" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
            </ul>
        </div>

        <!-- OTP Verification Section -->
        <section class="auth-section flex items-center justify-center">
            <div class="container mx-auto px-4 py-20">
                <div class="max-w-md mx-auto auth-card p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-white mb-2">Verify Code</h2>
                        <p class="text-gray-300">Enter the code sent to your email</p>
                    </div>

                    <?php if (isset($_SESSION['info'])): ?>
                        <div class="bg-green-500/20 border-l-4 border-green-500 text-green-100 p-4 mb-6 rounded">
                            <p><?php echo $_SESSION['info']; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (count($errors) > 0): ?>
                        <div class="bg-red-500/20 border-l-4 border-red-500 text-red-100 p-4 mb-6 rounded">
                            <?php foreach ($errors as $showerror): ?>
                                <p><?php echo $showerror; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" autocomplete="off" class="space-y-6">
                        <div>
                            <label for="otp" class="block text-sm font-medium text-gray-300 mb-2">Verification Code</label>
                            <input type="number" name="otp" id="otp" placeholder="Enter code" required 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>

                        <button type="submit" name="check-reset-otp" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobileMenuButton');
                const mobileMenu = document.getElementById('mobileMenu');
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            });
        </script>
    </body>
    </html>
    <?php
}

function renderNewPassword() {
    global $errors;
    if (!isset($_SESSION['email'])) {
        header('Location: auth.php?page=login');
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Set New Password | ReadyUp</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .gradient-text {
                background: linear-gradient(90deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .auth-section {
                background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('images/back2.png');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
            }
            .auth-card {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
            .input-field {
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
                transition: all 0.3s ease;
            }
            .input-field:focus {
                background: rgba(255, 255, 255, 0.2);
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            }
            .input-field::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }
        </style>
    </head>
    <body class="font-sans antialiased">

        <!-- Header -->
        <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="index.php" class="flex items-center">
                    <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                    <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                </a>
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><a href="index.php" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                        <li><a href="about.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                        <li><a href="#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Sectors</a></li>
                        <li><a href="contact.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
                    </ul>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="auth.php?page=signup" class="bg-[#276EF1] hover:bg-[#1F5A89] text-white font-semibold py-2 px-6 rounded-full transition duration-300">
                        Sign Up
                    </a>
                    <button class="md:hidden text-white text-2xl" id="mobileMenuButton"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </header>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-[#121823] py-4 px-4" id="mobileMenu">
            <ul class="space-y-4">
                <li><a href="index.php" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
                <li><a href="about.html" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
                <li><a href="#sectors" class="block text-white hover:text-blue-300 transition duration-300">Sectors</a></li>
                <li><a href="contact.html" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
            </ul>
        </div>

        <!-- New Password Section -->
        <section class="auth-section flex items-center justify-center">
            <div class="container mx-auto px-4 py-20">
                <div class="max-w-md mx-auto auth-card p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-white mb-2">Set New Password</h2>
                        <p class="text-gray-300">Create a strong password for your account</p>
                    </div>

                    <?php if (isset($_SESSION['info'])): ?>
                        <div class="bg-green-500/20 border-l-4 border-green-500 text-green-100 p-4 mb-6 rounded">
                            <p><?php echo $_SESSION['info']; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (count($errors) > 0): ?>
                        <div class="bg-red-500/20 border-l-4 border-red-500 text-red-100 p-4 mb-6 rounded">
                            <?php foreach ($errors as $showerror): ?>
                                <p><?php echo $showerror; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" autocomplete="off" class="space-y-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                            <input type="password" name="password" id="password" placeholder="Create new password" required 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>

                        <div>
                            <label for="cpassword" class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                            <input type="password" name="cpassword" id="cpassword" placeholder="Confirm your password" required 
                                   class="input-field w-full px-4 py-3 rounded-lg focus:outline-none">
                        </div>

                        <button type="submit" name="change-password" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobileMenuButton');
                const mobileMenu = document.getElementById('mobileMenu');
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            });
        </script>
    </body>
    </html>
    <?php
}

function renderPasswordChanged() {
    if (!isset($_SESSION['info'])) {
        header('Location: auth.php?page=login');
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Password Changed | ReadyUp</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .gradient-text {
                background: linear-gradient(90deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .auth-section {
                background: linear-gradient(rgba(18, 24, 35, 0.85), rgba(18, 24, 35, 0.85)), url('images/back2.png');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
            }
            .auth-card {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }
        </style>
    </head>
    <body class="font-sans antialiased">

        <!-- Header -->
        <header class="bg-[#121823] sticky top-0 z-50 shadow-lg">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="index.php" class="flex items-center">
                    <img src="images/Readyup.png" alt="ReadyUp Logo" class="h-10 w-10">
                    <span class="text-2xl font-bold text-white ml-2">Ready<span class="gradient-text">Up</span></span>
                </a>
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><a href="index.php" class="text-white hover:text-blue-300 transition duration-300 font-medium">Home</a></li>
                        <li><a href="about.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">About</a></li>
                        <li><a href="#sectors" class="text-white hover:text-blue-300 transition duration-300 font-medium">Sectors</a></li>
                        <li><a href="contact.html" class="text-white hover:text-blue-300 transition duration-300 font-medium">Contact</a></li>
                    </ul>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="auth.php?page=signup" class="bg-[#276EF1] hover:bg-[#1F5A89] text-white font-semibold py-2 px-6 rounded-full transition duration-300">
                        Sign Up
                    </a>
                    <button class="md:hidden text-white text-2xl" id="mobileMenuButton"><i class="fas fa-bars"></i></button>
                </div>
            </div>
        </header>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-[#121823] py-4 px-4" id="mobileMenu">
            <ul class="space-y-4">
                <li><a href="index.php" class="block text-white hover:text-blue-300 transition duration-300">Home</a></li>
                <li><a href="about.html" class="block text-white hover:text-blue-300 transition duration-300">About</a></li>
                <li><a href="#sectors" class="block text-white hover:text-blue-300 transition duration-300">Sectors</a></li>
                <li><a href="contact.html" class="block text-white hover:text-blue-300 transition duration-300">Contact</a></li>
            </ul>
        </div>

        <!-- Password Changed Section -->
        <section class="auth-section flex items-center justify-center">
            <div class="container mx-auto px-4 py-20">
                <div class="max-w-md mx-auto auth-card p-8">
                    <?php if (isset($_SESSION['info'])): ?>
                        <div class="bg-green-500/20 border-l-4 border-green-500 text-green-100 p-4 mb-6 rounded text-center">
                            <p><?php echo $_SESSION['info']; ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="text-center">
                        <button type="submit" name="login-now"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300">
                            Login Now
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobileMenuButton');
                const mobileMenu = document.getElementById('mobileMenu');
                mobileMenuButton.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            });
        </script>
    </body>
    </html>
    <?php
}
