<div id="slides" class="carousel slide carousel-cus" data-ride="carousel">
    <ul class="carousel-indicators">
        <li data-target="#slides" data-slide-to="0" class="active"></li>
        <li data-target="#slides" data-slide-to="1"></li>
        <li data-target="#slides" data-slide-to="2"></li>
    </ul>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="<?php echo base_url() . 'public/front/img/ini3.jpeg'; ?>" alt="FriesBrgrImg">
            <div class="carousel-caption text-left">
                <h1 class="display-2">Lapar?!</h1>
                <h3>Bagus, kami di sini untuk melayani Anda</h3>
                <a href="<?php echo base_url() . 'restaurant/index' ?>" class="btn btn-outline-light btn-lg">Pesan
                    Sekarang</a>
                <a href="<?php echo base_url() . 'restaurant/index' ?>" class="btn btn-primary btn-lg">Lihat Menu</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="<?php echo base_url() . 'public/front/img/pd3.jpeg'; ?>" alt="Spaghetti">
            <div class="carousel-caption text-right">
                <h1 class="display-2">Lapar?!</h1>
                <h3>Bagus, kami di sini untuk melayani Anda</h3>
                <a href="<?php echo base_url() . 'restaurant/index' ?>" class="btn btn-outline-light btn-lg">Pesan
                    Sekarang</a>
                <a href="<?php echo base_url() . 'restaurant/index' ?>" class="btn btn-primary btn-lg">Lihat Menu</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="<?php echo base_url() . 'public/front/img/pd2.jpeg'; ?>" alt="corn">
            <div class="carousel-caption text-right">
                <h1 class="display-2">Lapar?!</h1>
                <h3>Bagus, kami di sini untuk melayani Anda</h3>
                <a href="<?php echo base_url() . 'restaurant/index' ?>" class="btn btn-outline-light btn-lg">Pesan
                    Sekarang</a>
                <a href="<?php echo base_url() . 'restaurant/index' ?>" class="btn btn-primary btn-lg">Lihat Menu</a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid padding">
    <div class="row text-center welcome">
        <div class="col-12">
            <h1 class="display-4">3 Langkah Mudah untuk Diikuti</h1>
        </div>
        <hr>
        <div class="col-12">
            <p class="lead">Cara termudah untuk mendapatkan makanan Anda. Sistem Pemesanan Makanan menyediakan
                pengiriman segar
                dalam waktu 30 menit dan memberikan makanan gratis jika pesanan tidak tepat waktu. Jadi jangan tunggu
                dan mulai pesan sekarang juga!</p>
        </div>
    </div>
</div>
<div class="container-fluid padding">
    <div class="row text-center padding">
        <div class="col-xs-12 col-sm-6 col-md-4">
            <i class="fas fa-utensils"></i>
            <h3>Pilih Restoran</h3>
            <p>Hal pertama yang dapat Anda lakukan adalah memilih dari mitra restoran kami dengan mudah!</p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <i class="fas fa-bullseye"></i>
            <h3>Pilih Hidangan Lezat</h3>
            <p>Kami memiliki beragam menu dari berbagai restoran pengiriman makanan online!</p>
        </div>
        <div class="col-sm-12 col-md-4">
            <i class="fas fa-clipboard-check"></i>
            <h3>Pengambilan atau Pengiriman</h3>
            <p>Setelah semuanya selesai, makanan akan diantar atau Anda dapat mengambilnya sesuai pilihan Anda!</p>
        </div>
    </div>
    <hr class="my-4">
</div>
<div class="container-fluid padding">
    <div class="row welcome text-center welcome">
        <div class="col-12">
            <h1 class="display-4">Hidangan Populer</h1>
        </div>
        <hr>
    </div>
</div>
<div class="container-fluid padding dish-card">
    <div class="row">
        <?php if (!empty($dishesh)) { ?>
            <?php foreach ($dishesh as $dish) { ?>
                <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                    <div class="card mb-4 shadow-sm">
                        <?php $image = $dish['img']; ?>
                        <img class="card-img-top" src="<?php echo base_url() . 'public/uploads/dishesh/thumb/' . $image; ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title">
                                    <?php echo $dish['name']; ?>
                                </h4>
                            </div>
                            <h4 class="text-muted"><b>Rp.
                                    <?php echo $dish['price']; ?>
                                </b></h4>
                            <p class="card-text">
                                <?php echo $dish['about']; ?>
                            </p>
                            <a href="<?php echo base_url() . 'Dish/addToCart/' . $dish['d_id']; ?>" class="btn btn-primary"><i
                                    class="fas fa-cart-plus"></i> Tambahkan ke Keranjang</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="jumbotron">
                <h1>Tidak ada data</h1>
            </div>
        <?php } ?>
    </div>
    <hr class="my-4">
</div>
<div class="container-fluid padding">
    <div class="row text-center padding">
        <div class="col-12">
            <h2>Hubungi Kami</h2>
        </div>
        <div class="col-12 social padding">
            <a href=""><i class="fab fa-facebook"></i></a>
            <a href=""><i class="fab fa-twitter"></i></a>
            <a href=""><i class="fab fa-google-plus-g"></i></a>
            <a href=""><i class="fab fa-instagram"></i></a>
            <a href=""><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</div>
<hr class="my-4">
<section id="contact-us" class="container shadow my-4 p-4"
>
    <!--Judul Bagian-->
    <?php if ($this->session->flashdata('msg') != ""): ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('msg'); ?>
        </div>
    <?php endif ?>
    <h2 class="text-center my-2 font-weight-bold">Hubungi Kami</h2>
    <p class="text-center">Selalu Ada Untuk Anda</p>
    <!--Deskripsi Bagian-->
    <p class="text-center mx-auto mb-5"></p>
    <form name="contact-form" action="<?php echo base_url() . 'home/sendMail'; ?>" id="myForm" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="mb-0" for="name" class="">Nama Lengkap*</label>
                    <input type="text" id="name" name="name" class="form-control" required <?php set_value("name"); ?>>
                    <span></span>
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <label class="mb-0" for="email" class="">Email*</label>
                    <input type="text" id="email" name="email" class="form-control" required <?php set_value("email"); ?>>
                    <span></span>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-2">
                    <label class="mb-0" for="subject" class="">Subjek</label>
                    <input type="text" id="subject" name="subject" class="form-control" <?php set_value("subject"); ?>>
                    <span></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="mb-2">
                    <label class="mb-0" for="message">Pesan Anda*</label>
                    <textarea type="text" id="message" name="message" rows="2" class="form-control"
                        required><?php set_value("message"); ?></textarea>
                    <span></span>
                </div>
            </div>
        </div>
        <div class="status text-danger font-weight-bold my-2"></div>
        <button class="btn btn-info" type="submit">Kirim</button>
    </form>
</section>
<script>
    // Skrip validasi kontak
    const form = document.getElementById('myForm');
    const userName = document.getElementById('name');
    const email = document.getElementById('email');
    const subject = document.getElementById('subject');
    const message = document.getElementById('message');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        validate();
    })

    const isEmail = (emailVal) => {
        var re =
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(emailVal)) {
            return "fail";
        }
    }

    const sendData = (sRate, count) => {
        if (sRate === count) {
            event.currentTarget.submit();
        }
    }

    const successMsg = () => {
        let formCon = document.getElementsByClassName('form-control');
        var count = formCon.length - 1;
        for (var i = 0; i < formCon.length; i++) {
            if (formCon[i].className === "form-control success") {
                var sRate = 0 + i;
                sendData(sRate, count);
            } else {
                return false;
            }
        }
    }

    const validate = () => {
        const userNameVal = userName.value.trim();
        const emailVal = email.value.trim();
        const subjectVal = subject.value.trim();
        const messageVal = message.value.trim();

        // Validasi nama pengguna
        if (userNameVal === "") {
            setErrorMsg(userName, 'nama tidak boleh kosong');
        } else if (!isNaN(userNameVal)) {
            setErrorMsg(userName, 'hanya karakter yang diperbolehkan');
        } else {
            setSuccessMsg(userName);
        }

        // Validasi email
        if (emailVal === "") {
            setErrorMsg(email, 'email tidak boleh kosong');
        } else if (isEmail(emailVal) === "fail") {
            setErrorMsg(email, 'masukkan email yang valid');
        } else {
            setSuccessMsg(email);
        }

        // Validasi subjek
        if (subjectVal === "") {
            setErrorMsg(subject, 'subjek tidak boleh kosong');
        } else {
            setSuccessMsg(subject);
        }

        // Validasi pesan
        if (messageVal === "") {
            setErrorMsg(message, 'pesan tidak boleh kosong');
        } else {
            setSuccessMsg(message);
        }

        successMsg();
    }

    function setErrorMsg(ele, msg) {

        const formCon = ele.parentElement;
        const formInput = formCon.querySelector('.form-control');
        const span = formCon.querySelector('span');
        span.innerText = msg;
        formInput.className = "form-control is-invalid";
        span.className = "invalid-feedback font-weight-bold"
    }

    function setSuccessMsg(ele) {
        const formCon = ele.parentElement;
        const formInput = formCon.querySelector('.form-control');
        formInput.className = "form-control success";
    }
</script>