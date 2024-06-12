<?php
  $senderId = $_GET['id'];

  $syn = "SELECT * FROM CHAT_ROOM WHERE senderId = $senderId OR receriverId = $senderId AND "
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Chat</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Custom Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="../styles/styles.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
    .bg-ouryellow {
            background-color: #FFCC01;
        }
    .price-text {
            font-family: Arial, sans-serif; /* Memilih font yang jelas dan mudah dibaca */
            font-size: 24px; /* Ukuran teks yang lebih besar */
            color: #007bff; /* Warna biru yang menonjol */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Bayangan teks untuk memberikan sedikit dimensi */
            transition: all 0.3s ease; /* Efek transisi halus saat dihover */
        }

        .price-text:hover {
            transform: scale(1.1); /* Efek perbesaran saat dihover */
            color: #0056b3; /* Warna biru yang lebih gelap saat dihover */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Bayangan teks yang lebih kuat saat dihover */
        }

        .card-body {
            position: relative;
            height: 400px;
            overflow-y: auto; /* Menambahkan overflow-y untuk scrolling vertikal */
        }


    </style>
</head>

<body>
    <div class="navbar w-100 bg-ouryellow ">
        <div class="container-fluid d-flex justify-content-between">
            <div class="input-group w-75">
                <h1>Your Classes</h1>
            </div>
            <div class="container w-25 row">
                <div class="container col-3">
                    <i class="bi bi-person-circle text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-filter text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-cart-fill text-white" style="font-size: 30px;"></i></a></th>
                </div>
                <div class="container col-3">
                    <i class="bi bi-list text-white" style="font-size: 30px;"></i></a></th>
                </div>
            </div>
        </div>
    </div>

    <section style="background-color: #eee;">
        <div class="container py-5">
      
          <div class="row d-flex justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
      
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center p-3"
                  style="border-top: 4px solid #ffa900;">
                  <h5 class="mb-0">Chat messages</h5>
                  <div class="d-flex flex-row align-items-center">
                    <span class="badge bg-warning me-3">20</span>
                    <i class="fas fa-minus me-3 text-muted fa-xs"></i>
                    <i class="fas fa-comments me-3 text-muted fa-xs"></i>
                    <i class="fas fa-times text-muted fa-xs"></i>
                  </div>
                </div>
                <div class="card-body" data-mdb-perfect-scrollbar-init style="position: relative; height: 400px">
      
                  <div class="d-flex justify-content-between">
                    <p class="small mb-1">Javier Vittorio</p>
                    <p class="small mb-1 text-muted">23 Jan 2:00 pm</p>
                  </div>
                  <div class="d-flex flex-row justify-content-start">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                      alt="avatar 1" style="width: 45px; height: 100%;">
                    <div>
                      <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">For what reason
                        would it
                        be advisable for me to think about business content?</p>
                    </div>
                  </div>
      
                  <div class="d-flex justify-content-between">
                    <p class="small mb-1 text-muted">23 Jan 2:05 pm</p>
                    <p class="small mb-1">Hansel Meinhard</p>
                  </div>
                  <div class="d-flex flex-row justify-content-end mb-4 pt-1">
                    <div>
                      <p class="small p-2 me-3 mb-3 text-white rounded-3 bg-warning">Thank you for your believe in
                        our
                        supports</p>
                    </div>
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                      alt="avatar 1" style="width: 45px; height: 100%;">
                  </div>
      
                  <div class="d-flex justify-content-between">
                    <p class="small mb-1">Javier Vittorio</p>
                    <p class="small mb-1 text-muted">23 Jan 5:37 pm</p>
                  </div>
                  <div class="d-flex flex-row justify-content-start">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                      alt="avatar 1" style="width: 45px; height: 100%;">
                    <div>
                      <p class="small p-2 ms-3 mb-3 rounded-3" style="background-color: #f5f6f7;">Lorem ipsum dolor
                        sit amet
                        consectetur adipisicing elit similique quae consequatur</p>
                    </div>
                  </div>
      
                  <div class="d-flex justify-content-between">
                    <p class="small mb-1 text-muted">23 Jan 6:10 pm</p>
                    <p class="small mb-1">Hansel Meinhard</p>
                  </div>
                  <div class="d-flex flex-row justify-content-end mb-4 pt-1">
                    <div>
                      <p class="small p-2 me-3 mb-3 text-white rounded-3 bg-warning">Dolorum quasi voluptates quas
                        amet in
                        repellendus perspiciatis fugiat</p>
                    </div>
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                      alt="avatar 1" style="width: 45px; height: 100%;">
                  </div>
      
                </div>
                <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                  <div class="input-group mb-0">
                    <input type="text" class="form-control" placeholder="Type message"
                      aria-label="Recipient's username" aria-describedby="button-addon2" />
                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-warning" type="button" id="button-addon2" style="padding-top: .55rem;">
                      Send
                    </button>
                  </div>
                </div>
              </div>
      
            </div>
          </div>
      
        </div>
      </section>


    
</body>

</html>