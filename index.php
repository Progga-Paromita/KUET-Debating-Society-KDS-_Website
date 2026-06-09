<?php
if (!isset($_SESSION['role']) && isset($_COOKIE['role'])) {

    $_SESSION['role'] = $_COOKIE['role'];
    $_SESSION['user_id'] = $_COOKIE['user_id'];
}
$page = $_GET['page'] ?? "home";

include "includes/header.php";

switch ($page) {
    case "home":
        include "pages/home.php";
        break;

    case "role_select":
        include "pages/role_select.php";
        break;

    case "signup":
        include "pages/signup.php";
        break;

    case "login":
        include "pages/login.php";
        break;

    case "profile_admin":
        include "pages/profile_admin.php";
        break;

    case "profile_member": 
        include "pages/profile_member.php";
         break;

    case "logout":
        include "pages/logout.php";
        break;

    case "admin_requests":
        include "pages/admin_requests.php";
        break;
    case "edit_member":
        include "pages/edit_member.php";
        break;
    case "edit_event":
        include "pages/edit_event.php";
        break;    
    case "edit_resource":
        include "pages/edit_resource.php";
        break;
    case "test_cookie":
        include "pages/test_cookie.php";
        break;
    case "test_session":
        include "pages/test_session.php";
        break;
    case "submit_query":
        include "pages/submit_query.php";
        break;
    case "reply_query":
        include "pages/reply_query.php";
        break;
    case "admin_queries":
        include "pages/admin_queries.php";
        break;
    case "notif_count":
        include "pages/notif_count.php";
        break;
    case "edit_profile":
        include "pages/edit_profile.php";
        break;
    default:
        echo "Page not found";
}

include "includes/footer.php";

if (isset($pages[$page]) && file_exists($pages[$page])) {
    include $pages[$page];
} else {
    echo "Page not found!";
}
?>

