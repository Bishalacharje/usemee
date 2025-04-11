<meta charset="utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesdesign" name="author" />
<!-- App favicon -->
<link rel="shortcut icon" href="assets/images/fav.png">

<!-- jquery.vectormap css -->
<link href="assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet"
    type="text/css" />


<!-- DataTables -->
<link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- Responsive datatable examples -->
<link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />



<!-- Lightbox css -->
<link href="assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />

<!-- Bootstrap Css -->
<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />






<style>
    .backBtn {
        font-size: 30px;
        cursor: pointer;
        color: #0f9bf2;
    }

    .table-con {}

    .table-con td {
        min-width: 100px;
    }

    .text-input {
        border: none;
        outline: none;
        font-size: 24px;
        font-weight: bold !important;
        color: red;
    }

    .page-content {
        padding: calc(70px + 24px) 0 60px;
    }

    .myTableCon {
        overflow: auto;
    }

    .loadingSec {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.685);
        /* semi-transparent */
        backdrop-filter: blur(10px);
        /* blur effect */
        -webkit-backdrop-filter: blur(10px);
        /* for Safari support */
        z-index: 99999;
        transition: opacity 0.5s ease;
    }

    .loadingSec.hide {
        opacity: 0;
        pointer-events: none;
    }

    .loadingCon {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .loadingSec .logo img {
        height: 100px;
        margin-bottom: 8px;
    }

    /* From Uiverse.io by kerolos23 */
    .loader {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }

    .loader div {
        position: absolute;
        top: 33px;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #80B500;
        animation-timing-function: cubic-bezier(0, 1, 1, 0);
    }

    .loader div:nth-child(1) {
        left: 8px;
        animation: flip1 0.6s infinite;
    }

    .loader div:nth-child(2) {
        left: 8px;
        animation: flip2 0.6s infinite;
    }

    .loader div:nth-child(3) {
        left: 32px;
        animation: flip2 0.6s infinite;
    }

    .loader div:nth-child(4) {
        left: 56px;
        animation: flip3 0.6s infinite;
    }

    @keyframes flip1 {
        0% {
            transform: scale(0);
        }

        100% {
            transform: scale(1);
        }
    }

    @keyframes flip3 {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(0);
        }
    }

    @keyframes flip2 {
        0% {
            transform: translate(0, 0);
        }

        100% {
            transform: translate(24px, 0);
        }
    }




    @media (max-width: 700px) {
        .loginCard {
            height: 100vh;
            border-radius: 0;
        }

        .loginCard .card-body {

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .wrapper-page {
            margin: 0;
        }

        .auth-body-bg {
            height: 100vh;
        }
    }
</style>