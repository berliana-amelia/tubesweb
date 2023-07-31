<!-- Kode HTML untuk mengedit kategori restoran -->
<div class="container shadow-container">
    <h2 class="p-2 text-center">Edit Kategori Restoran "<?php echo $cat['c_name'];?>"</h2>
    <form action="<?php echo base_url().'admin/category/edit/'.$cat['c_id'];?>" class="container" method="POST" id="myForm">
        <div class="form-group">
            <label for="category">Kategori</label>
            <input type="text" class="form-control" id="category" placeholder="Masukkan Kategori" name="category" value="<?php echo set_value('category', $cat['c_name']);?>">
            <?php echo form_error('category'); ?>
            <span></span>
        </div>
        <button type="submit" class="btn btn-primary mr-2">Ubah</button>
        <a class="btn btn-secondary" href="<?php echo base_url().'admin/category/index';?>">Kembali</a>
    </form>
</div>
<script>
    // Kode JavaScript untuk validasi dan mengirim data form

    // Mengambil elemen-elemen yang dibutuhkan dari DOM
    const form = document.getElementById('myForm');
    const category = document.getElementById('category');

    // Menambahkan event listener pada form ketika submit
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        validate();
    })

    // Fungsi untuk mengirim data form ke server jika semua validasi sukses
    const sendData = (sRate, count) => {
        if (sRate === count) {
            event.currentTarget.submit();
        }
    }

    // Fungsi untuk memeriksa apakah semua input telah divalidasi dengan sukses
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

    // Fungsi untuk melakukan validasi pada input kategori sebelum mengirimkan data
    const validate = () => {
        const categoryVal = category.value.trim();

        // Validasi kategori
        if (categoryVal === "") {
            setErrorMsg(category, 'kategori tidak boleh kosong');
        } else if (categoryVal.length <= 4 || categoryVal.length >= 16) {
            setErrorMsg(category, 'panjang kategori harus antara 5 dan 15 karakter');
        } else {
            setSuccessMsg(category);
        }
        successMsg();
    }

    // Fungsi untuk menampilkan pesan error pada input yang tidak valid
    function setErrorMsg(ele, msg) {
        const formCon = ele.parentElement;
        const formInput = formCon.querySelector('.form-control');
        const span = formCon.querySelector('span');
        span.innerText = msg;
        formInput.className = "form-control is-invalid";
        span.className = "invalid-feedback font-weight-bold"
    }

    // Fungsi untuk menampilkan pesan sukses pada input yang valid
    function setSuccessMsg(ele) {
        const formCon = ele.parentElement;
        const formInput = formCon.querySelector('.form-control');
        formInput.className = "form-control success";
    }

</script>
