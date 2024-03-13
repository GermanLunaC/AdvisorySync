<?php 
    require 'app/models/admin/Admin.php';
    require 'app/models/User.php';
    require 'app/models/Advisory.php';
    require 'app/models/Category.php';
    require 'app/models/SubCategory.php';
    require 'app/models/Review.php';
    require 'app/models/messageUser.php';
    require 'app/models/UserRoom.php';
    require 'app/models/Notification.php';
    
    class AdminController{
        
        private $admin;
        private $user;
        private $advisory;
        private $category;
        private $subCategory;
        private $review;
        private $messageUser;
        private $userRoom;
        private $notification;

        public function __construct() {
            $this->admin = new Admin(Connection::conn());
            $this->user = new User(Connection::conn());
            $this->advisory = new Advisory(Connection::conn());
            $this->category = new Category(Connection::conn());
            $this->subCategory = new SubCategory(Connection::conn());
            $this->review = new Review(Connection::conn());
            $this->messageUser = new MessageUser(Connection::conn());
            $this->userRoom = new UserRoom(Connection::conn());
            $this->notification = new Notification(Connection::conn());
        }
        
        public function pagination($page, $total_resultados) {
            // Configuración de la paginación
            $resultados_por_pagina = 5; // Número de resultados por página

            if (isset($page)) {
                $pagina_actual = $page;
            } else {
                $pagina_actual = 1;
            }
            $empezar_desde = ($pagina_actual - 1) * $resultados_por_pagina;

            // Calcular el número total de páginas
            $total_paginas = ceil($total_resultados / $resultados_por_pagina);  

            return array($resultados_por_pagina, $empezar_desde, $total_paginas, $pagina_actual);
        }

        public function home() {

            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }
            // Lógica para la página de inicio estática

            $countAdvisory = $this->admin->countAdvisory();
            $countUser = $this->admin->countUser();
            $revenue = $this->admin->revenue();
            $lastAdvisory = $this->admin->lastAdvisory();
            $topUser = $this->admin->topUser();

            include 'app/views/admin/home.php';
        }

        public function users() {
            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }

            $countAdviser = $this->admin->countAdviser();
            $countStudent = $this->admin->countStudent();

            $page = isset($_GET['pagina']) ? $_GET['pagina'] : null;

            $total_resultados = $this->admin->allUsers();

            $pagination = $this->pagination($page, $total_resultados);

            $resultados_por_pagina = $pagination[0];
            $empezar_desde = $pagination[1];
            $total_paginas = $pagination[2];
            $pagina_actual = $pagination[3];

            $users = $this->user->getAllUsers($empezar_desde, $resultados_por_pagina);

            if (isset($_POST['delete'])) {

                $id = $_POST['id_usuario'];

                $_SESSION['message'] = $this->user->deleteUser($id);

            }

            if (isset($_POST['btnSearch'])) {
                if ($_POST['search'] == '') {
                    $users = $this->user->getAllUsers($empezar_desde, $resultados_por_pagina);
                }else{
                    $users = $this->user->getUser($_POST['search']);
                }
            }

            include 'app/views/admin/users.php';

            if (isset($_SESSION['message'])) {
                if ($_SESSION['message'] == true) {
                    echo "
                        <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminacion',
                            text: 'Se elimino con exito el usuario',
                        }).then(function() {
                            window.location.href = 'http://localhost/advisorySync/admin/users';
                        });
                        </script> 
                        ";
                }else{
                    echo "
                    <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Eliminacion',
                        text: 'No se pudo eliminar',
                    }).then(function() {
                        window.location.href = 'http://localhost/advisorySync/admin/users';
                    });
                    </script> 
                    ";
                }
                
                unset($_SESSION['message']);
            }

        }

        public function advisories() {
            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }
            // Lógica para la página de inicio estática

            $moreUsedCategory = $this->admin->moreUsedCategory();

            $page = isset($_GET['pagina']) ? $_GET['pagina'] : null;
            
            $total_resultados = $this->admin->allAdvisory();

            $pagination = $this->pagination($page, $total_resultados);

            $resultados_por_pagina = $pagination[0];
            $empezar_desde = $pagination[1];
            $total_paginas = $pagination[2];
            $pagina_actual = $pagination[3];

            $advisories = $this->advisory->getAllAdvisories($empezar_desde, $resultados_por_pagina);

            if (isset($_POST['btnSearch'])) {
                if ($_POST['search'] == '') {
                    $advisories = $this->advisory->getAllAdvisories($empezar_desde, $resultados_por_pagina);
                }else{
                    $advisories = $this->advisory->getAdvisory($_POST['search']);
                }
            }

            if (isset($_POST['delete'])) {

                $id = $_POST['id_publi'];

                $_SESSION['message'] = $this->advisory->deleteAdvisory($id);

            }

            include 'app/views/admin/advisories.php';

            if (isset($_SESSION['message'])) {
                if ($_SESSION['message'] == true) {
                    echo "
                        <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminacion',
                            text: 'Se elimino con exito la publicacion',
                        }).then(function() {
                            window.location.href = 'http://localhost/advisorySync/admin/advisories';
                        });
                        </script> 
                        ";
                }else{
                    echo "
                    <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Eliminacion',
                        text: 'No se pudo eliminar',
                    }).then(function() {
                        window.location.href = 'http://localhost/advisorySync/admin/advisories';
                    });
                    </script> 
                    ";
                }
                
                unset($_SESSION['message']);
            }
        }

        public function categories() {
            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }

            $page = isset($_GET['pagina']) ? $_GET['pagina'] : null;

            $total_resultados = $this->admin->allCategories();

            $pagination = $this->pagination($page, $total_resultados);

            $resultados_por_pagina = $pagination[0];
            $empezar_desde = $pagination[1];
            $total_paginas = $pagination[2];
            $pagina_actual = $pagination[3];

            $categories = $this->category->getAllCategories($empezar_desde, $resultados_por_pagina);

            if (isset($_POST['delete'])) {

                $id = $_POST['id_categoria'];

                $_SESSION['message'] = $this->category->deleteCategory($id);

            }

            if (isset($_GET['id'])) {

                $id = $_GET['id'];

                $_SESSION['userUpdate'] = $this->category->getCategory($id);

            }

            if(isset($_POST['create'])){
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $imagen = $_FILES['imagen'];

                // if (isset($imagen) || isset($nombre) || isset($descripcion) ) {
                //     $_SESSION['crear'] = false;
                // }

                try {
                    $_SESSION['crear'] = $this->category->createCategory($nombre, $descripcion, 'https://picsum.photos/200/300');
                } catch (\Throwable $th) {
                    $_SESSION['crear'] = false;
                }
            }

            if(isset($_POST['update'])){
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $imagen = isset($_FILES['imagen']) ? $_SESSION['userUpdate'][0]['img'] : $_SESSION['userUpdate'][0]['img'] ;

                // if (isset($imagen) || isset($nombre) || isset($descripcion) ) {
                //     $_SESSION['crear'] = false;
                // }


                try {
                    $_SESSION['update'] = $this->category->updateCategory($_SESSION['userUpdate'][0]['id_categoria'],$nombre, $descripcion, $imagen);
                } catch (\Throwable $th) {
                    $_SESSION['update'] = false;
                }
            }

            if (isset($_POST['btnSearch'])) {
                if ($_POST['search'] == '') {
                    $categories = $this->category->getAllCategories($empezar_desde, $resultados_por_pagina);
                }else{
                    $categories = $this->category->getCategory($_POST['search']);
                }
            }

            // Lógica para la página de inicio estática
            include 'app/views/admin/categories.php';

            if (isset($_SESSION['message'])) {
                if ($_SESSION['message'] == true) {
                    echo "
                        <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminacion',
                            text: 'Se elimino con exito la categoria',
                        }).then(function() {
                            window.location.href = 'http://localhost/advisorySync/admin/categories';
                        });
                        </script> 
                        ";
                }else{
                    echo "
                    <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Eliminacion',
                        text: 'No se pudo eliminar',
                    }).then(function() {
                        window.location.href = 'http://localhost/advisorySync/admin/categories';
                    });
                    </script> 
                    ";
                }
                
                unset($_SESSION['message']);
            }

            if (isset($_SESSION['crear'])) {
                if ($_SESSION['crear'] == true) {
                    echo "
                        <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Creacion',
                            text: 'Se creo con exito la categoria',
                        }).then(function() {
                            window.location.href = 'http://localhost/advisorySync/admin/categories';
                        });
                        </script> 
                        ";
                }else{
                    echo "
                    <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Creacion',
                        text: 'No se pudo crear',
                    }).then(function() {
                        window.location.href = 'http://localhost/advisorySync/admin/categories';
                    });
                    </script> 
                    ";
                }
                
                unset($_SESSION['crear']);
            }
            if (isset($_SESSION['update'])) {
                if ($_SESSION['update'] == true) {
                    echo "
                        <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizacion',
                            text: 'Se actualizo con exito la categoria',
                        }).then(function() {
                            window.location.href = 'http://localhost/advisorySync/admin/categories';
                        });
                        </script> 
                        ";
                }else{
                    echo "
                    <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Creacion',
                        text: 'No se pudo actualizar',
                    }).then(function() {
                        window.location.href = 'http://localhost/advisorySync/admin/categories';
                    });
                    </script> 
                    ";
                }
                
                unset($_SESSION['userUpdate']);
                unset($_SESSION['update']);
            }

        }

        public function subCategories() {
            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }

            $categories = $this->admin->allCategory();

            $page = isset($_GET['pagina']) ? $_GET['pagina'] : null;

            $total_resultados = $this->admin->allSubCategories();

            $pagination = $this->pagination($page, $total_resultados);

            $resultados_por_pagina = $pagination[0];
            $empezar_desde = $pagination[1];
            $total_paginas = $pagination[2];
            $pagina_actual = $pagination[3];

            $subCategories = $this->subCategory->getAllSubCategories($empezar_desde, $resultados_por_pagina);

            if (isset($_GET['id_sub'])) {

                $id = $_GET['id_sub'];

                $_SESSION['subCategoryUpdate'] = $this->subCategory->getSubCategory($id);
            }

            if (isset($_POST['btnSearch'])) {
                if ($_POST['search'] == '') {
                    $subCategories = $this->subCategory->getAllSubCategories($empezar_desde, $resultados_por_pagina);
                }else{
                    $subCategories = $this->subCategory->getSubCategory($_POST['search']);
                }
            }

            if (isset($_POST['delete'])) {

                $id = $_POST['id_sub'];

                var_dump($id);

                $_SESSION['message'] = $this->subCategory->deleteSubCategory($id);

                var_dump($_SESSION['message']);

            }

            if(isset($_POST['create'])){
                $nombre = $_POST['nombre'];
                $id_categoria = $_POST['id_categoria'];

                try {
                    $_SESSION['crear'] = $this->subCategory->createSubCategory($nombre, $id_categoria);
                } catch (\Throwable $th) {
                    $_SESSION['crear'] = false;
                }
            }

            if(isset($_POST['update'])){
                $nombre = $_POST['nombre'];
                $id_sub = $_SESSION['subCategoryUpdate'][0]['id_sub'];
            
                try {
                    $_SESSION['update'] = $this->subCategory->updateSubCategory($nombre, $id_sub);
                } catch (\Throwable $th) {
                    $_SESSION['update'] = false;
                }
            }
            

            // Lógica para la página de inicio estática
            include 'app/views/admin/subCategories.php';

            if (isset($_SESSION['message'])) {
                if ($_SESSION['message'] == true) {
                    echo "
                        <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminacion',
                            text: 'Se elimino con exito la Sub Categoria',
                        }).then(function() {
                            window.location.href = 'http://localhost/advisorySync/admin/subCategories';
                        });
                        </script> 
                        ";
                }else{
                    echo "
                    <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Eliminacion',
                        text: 'No se pudo eliminar',
                    }).then(function() {
                        window.location.href = 'http://localhost/advisorySync/admin/subCategories';
                    });
                    </script> 
                    ";
                }
                
                unset($_SESSION['message']);
            }

            if (isset($_SESSION['crear'])) {
                if ($_SESSION['crear'] == true) {
                    echo "
                        <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Creacion',
                            text: 'Se creo con exito la sub categoria',
                        }).then(function() {
                            window.location.href = 'http://localhost/advisorySync/admin/subCategories';
                        });
                        </script> 
                        ";
                }else{
                    echo "
                    <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Creacion',
                        text: 'No se pudo crear',
                    }).then(function() {
                        window.location.href = 'http://localhost/advisorySync/admin/subCategories';
                    });
                    </script> 
                    ";
                }
                
                unset($_SESSION['crear']);

                if (isset($_SESSION['update'])) {
                    if ($_SESSION['update'] == true) {
                        echo "
                            <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualizacion',
                                text: 'Se actualizo con exito la Subcategoria',
                            }).then(function() {
                                window.location.href = 'http://localhost/advisorySync/admin/subCategories';
                            });
                            </script> 
                            ";
                    }else{
                        echo "
                        <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Creacion',
                            text: 'No se pudo actualizar',
                        }).then(function() {
                            window.location.href = 'http://localhost/advisorySync/admin/subCategories';
                        });
                        </script> 
                        ";
                    }
                    
                    unset($_SESSION['update']);
                }
            }
        }


        public function reviews() {
            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }

            $page = isset($_GET['pagina']) ? $_GET['pagina'] : null;

            $total_resultados = $this->admin->allReviews();

            $pagination = $this->pagination($page, $total_resultados);

            $resultados_por_pagina = $pagination[0];
            $empezar_desde = $pagination[1];
            $total_paginas = $pagination[2];
            $pagina_actual = $pagination[3];

            $reviews = $this->review->getAllReviews($empezar_desde, $resultados_por_pagina);

            if (isset($_POST['btnSearch'])) {
                if ($_POST['search'] == '') {
                    $reviews = $this->review->getAllReviews($empezar_desde, $resultados_por_pagina);
                }else{
                    $reviews = $this->review->getReview($_POST['search']);
                }
            }


            include 'app/views/admin/reviews.php';
        }

        public function messagesUser() {

            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }

            $page = isset($_GET['pagina']) ? $_GET['pagina'] : null;

            $total_resultados = $this->admin->allMessages();

            $pagination = $this->pagination($page, $total_resultados);

            $resultados_por_pagina = $pagination[0];
            $empezar_desde = $pagination[1];
            $total_paginas = $pagination[2];
            $pagina_actual = $pagination[3];

            $messages = $this->messageUser->getAllMessagesUser($empezar_desde, $resultados_por_pagina);

            if (isset($_POST['btnSearch'])) {
                if ($_POST['search'] == '') {
                    $messages = $this->messageUser->getAllMessagesUser($empezar_desde, $resultados_por_pagina);
                }else{
                    $messages = $this->messageUser->getMessage($_POST['search']);
                }
            }

            include 'app/views/admin/messagesUser.php';

        }

        public function UserRoom() {

            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }

            $page = isset($_GET['pagina']) ? $_GET['pagina'] : null;

            $total_resultados = $this->admin->allUserRoom();

            $pagination = $this->pagination($page, $total_resultados);

            $resultados_por_pagina = $pagination[0];
            $empezar_desde = $pagination[1];
            $total_paginas = $pagination[2];
            $pagina_actual = $pagination[3];

            $messages = $this->userRoom->getAllUserRoom($empezar_desde, $resultados_por_pagina);

            if (isset($_POST['btnSearch'])) {
                if ($_POST['search'] == '') {
                    $messages = $this->userRoom->getAllUserRoom($empezar_desde, $resultados_por_pagina);
                }else{
                    $messages = $this->userRoom->getUserRoom($_POST['search']);
                }
            }

            include 'app/views/admin/userRooms.php';

        }

        public function Notification() {

            if (!$_SESSION['usuario'] ){
                header('location: http://localhost/advisorysync/auth/login');
            }else{
                if ($_SESSION['usuario']['is_admin'] == false) {
                    header('location: http://localhost/advisorysync/static/home');
                }
            }

            $page = isset($_GET['pagina']) ? $_GET['pagina'] : null;

            $total_resultados = $this->admin->allNotification();

            $pagination = $this->pagination($page, $total_resultados);

            $resultados_por_pagina = $pagination[0];
            $empezar_desde = $pagination[1];
            $total_paginas = $pagination[2];
            $pagina_actual = $pagination[3];

            $messages = $this->notification->getAllNotifications($empezar_desde, $resultados_por_pagina);

            if (isset($_POST['btnSearch'])) {
                if ($_POST['search'] == '') {
                    $messages = $this->notification->getAllNotifications($empezar_desde, $resultados_por_pagina);
                }else{
                    $messages = $this->notification->getNotification($_POST['search']);
                }
            }

            include 'app/views/admin/notifications.php';

        }



        // Otros métodos para páginas estáticas según sea necesario
    }

?>