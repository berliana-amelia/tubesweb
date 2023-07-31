<!-- Kode HTML untuk mengedit item makanan -->
<div class="conatiner">
    <form id="myForm" action="<?php echo base_url().'admin/menu/edit/'.$dish['d_id'];?>" method="POST"
        class="form-container mx-auto  shadow-container" style="width:80%" enctype="multipart/form-data">
        <h3 class="mb-3 text-center">Edit Menu "<?php echo $dish['name']; ?>"</h3>
        <div class="form-group">
            <label class="control-label">Pilih Restoran</label>
            <select name="rname" id="resname"
                class="form-control <?php echo (form_error('rname') != "") ? 'is-invalid' : '';?>">
                <option>--Pilih Restoran--</option>
                <?php 
                if (!empty($stores)) { 
                    foreach($stores as $store) {
                        ?>
                <option value="<?php echo $store['r_id'];?>">
                    <?php echo set_select('resname', $store['name']);?>
                    <?php echo $store['name'];?>
                </option>
                <?php }
                }
                ?>
            </select>
            <span></span>
            <?php echo form_error('rname');?>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nama Makanan</label>
                    <input type="text" class="form-control my-2 
                    <?php echo (form_error('name') != "") ? 'is-invalid' : '';?>" name="name" id="name"
                        placeholder="Masukkan nama makanan" value="<?php echo set_value('name', $dish['name']); ?>">
                    <span></span>
                    <?php echo form_error('name'); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price">Harga</label>
                    <input type="Price" class="form-control my-2
                    <?php echo (form_error('price') != "") ? 'is-invalid' : '';?>" id="price" name="price"
                        placeholder="Masukkan Harga" value="<?php echo set_value('price', $dish['price']); ?>">
                    <span></span>
                    <?php echo form_error('price'); ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="about">Tentang</label>
                    <input type="text" class="form-control my-2
                    <?php echo (form_error('about') != "") ? 'is-invalid' : '';?>" id="about" name="about"
                        placeholder="Tentang" value="<?php echo set_value('about', $dish['about']); ?>">
                    <span></span>
                    <?php echo form_error('about'); ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="img">Gambar Makanan</label>
                    <input type="file" id="image" name="image" placeholder="Masukkan Gambar" class="form-control my-2
                    <?php echo(!empty($errorImageUpload))  ? 'is-invalid' : '';?>">
                    <?php echo (!empty($errorImageUpload)) ? $errorImageUpload : '';?>

                    <?php if($dish['img'] != "" && file_exists('./public/uploads/dishesh/thumb/'.$dish['img'])) { ?>

                    <img src="<?php echo base_url().'public/uploads/dishesh/thumb/'.$dish['img'];?>">

                    <?php } else { ?>
                    <img width="300" src="<?php echo base_url().'public/uploads/no-image.png'?>">
                    <?php } ?>
                    <span></span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary ml-2">Simpan Perubahan</button>
        <a href="<?php echo base_url().'admin/menu/index'?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<script>
const form = document.getElementById('myForm');
const resname = document.getElementById("resname");
const dishname = document.getElementById("name");
const price = document.getElementById("price");
const about = document.getElementById("about");
const image = document.getElementById("image");

form.addEventListener('submit', (event) => {
    event.preventDefault();
    validate();
})

const sendData = (sRate, count) => {
    if (sRate === count) {
        event.currentTarget.submit();
    }
}

const successMsg = () => {
    let formCon = document.getElementsByClassName('form-control');
    var count = formCon.length - 2;
    for (var i = 0; i < formCon.length; i++) {
        if (formCon[i].className === "form-control my-2 success") {
            var sRate = 0 + i;
            sendData(sRate, count);
        } else {
            return false;
        }
    }
}

const validate = () => {
    const resnameVal = resname.value.trim();
    const dishnameVal = dishname.value.trim();
    const priceVal = price.value.trim();
    const aboutVal = about.value.trim();
    const imageVal = image.value.trim();

    if (resnameVal === "--Pilih Restoran--") {
        setErrorMsg(resname, 'pilih nama restoran');
    } else {
        setSuccessMsg(resname);
    }
    if (dishnameVal === "") {
        setErrorMsg(dishname, 'nama makanan tidak boleh kosong');
    } else {
        setSuccessMsg(dishname);
    }
    if (priceVal === "") {
        setErrorMsg(price, 'harga tidak boleh kosong');
    } else {
        setSuccessMsg(price);
    }
    if (aboutVal === "") {
        setErrorMsg(about, 'tentang tidak boleh kosong');
    } else {
        setSuccessMsg(about);
    }

    successMsg();

}

function setErrorMsg(ele, errormsgs) {
    const formCon = ele.parentElement;
    const formInput = formCon.querySelector('.form-control');
    const span = formCon.querySelector('span');
    span.innerText = errormsgs;
    formInput.className = "form-control my-2 is-invalid";
    span.className = "invalid-feedback font-weight-bold";
}

function setSuccessMsg(ele) {
    const formGroup = ele.parentElement;
    const formInput = formGroup.querySelector('.form-control');
    formInput.className = "form-control my-2 success";
}
</script>
