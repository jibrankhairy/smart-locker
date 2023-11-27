<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="stylee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");

        * {
            margin: 0;
            padding: 0;
            border: none;
            outline: none;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
        }

        .sidebar {
            position: sticky;
            top: 0;
            left: 0;
            bottom: 0;
            width: 110px;
            height: 100vh;
            padding: 0 1.7rem;
            color: #fff;
            overflow: hidden;
            transition: all 0.5s linear;
            background: #6495ED;
        }

        .sidebar:hover {
            width: 270px;
            transition: 0.5s;
        }

        .logo {
            height: 80px;
            padding: 16px;
        }

        .menu {
            height: 88%;
            position: relative;
            list-style: none;
            padding: 0;
        }

        .menu li {
            padding: 1rem;
            margin: 8px 0;
            border-radius: 8px;
            transition: all 0.5s ease-in-out;
        }

        .menu li:hover, .active {
            background: #e0e0e058;
        }

        .menu a {
            color: #fff;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .menu a span {
            overflow: hidden;
        }

        .menu a i {
            font-size: 1.2rem;
        }

        .logout {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .main--content {
            position: relative;
            background: #ebe9e9;
            width: 100%;
            padding: 1rem;
        }

        .header-wrapper {
            width: 50px;
            height: 50px;
            cursor: pointer;
            border-radius: 50%;
        }

        .header--wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            background: #fff;
            border-radius: 10px;
            padding: 10px 2rem;
            margin-bottom: 1rem;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                width: 100%;
                padding: 1rem;
            }

            .sidebar:hover {
                width: 100%;
            }

            .menu li {
                width: 100%;
            }

            .main--content {
                padding: 1rem;
            }

            .header--wrapper {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
   <div class="sidebar">
    <div class="logo">
        <ul class="menu">
          <li class="active">
            <a href="#">
            <i class="fas fa-home"></i> 
                <span>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="#">
            <i class="fas fa-lock"></i>
                <span>Locker</span>
            </a>
          </li>  
          <li>
            <a href="#">
            <i class="fas fa-users"></i>
                <span>Data User</span>
            </a>
          </li>
          <li>
            <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
          </li>
        </ul> 
    </div>
   </div> 
   <div class="main--content">
    <div class="header-wrapper">
      <div class="header--title">
        <!-- <span>Primary</span> -->
        <h2>Dashboard Admin</h2>
      </div>
      <div class="user--info">
   </div>
    
</body>
</html>
