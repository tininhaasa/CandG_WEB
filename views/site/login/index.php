<div class="login" id="login-content" style="padding: 20px 0;">
    <div class="container">
        <h3 class="text-center mt-1" style="font-weight: 100;"><span>Faça o</span> <b>login</b></h3>

        <div name="login" class="mt-2">
            <div class="input-group user">
                <div class="input-group-prepend text-center">
                    <label for="login"><i class="fa-solid fa-user"></i></label>
                </div>
                <input type="text" class="form-control" id="login" name="login" placeholder="Digite seu E-mail">
            </div>
            <div class="input-group pass" style="margin:0">
                <div class="input-group-prepend text-center">
                    <label for="password"><i class="fa-solid fa-unlock-keyhole"></i></label>
                </div>
                <input type="password" class="form-control" id="password" placeholder="Digite sua senha">
                <div class="input-group-append text-center ">
                    <div class="reveal-pass">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                </div>
            </div>
            <div class="forget-pass">
                <a class="font-color" href="<?= $url ?>/esqueci-senha">Esqueci minha senha</a>
            </div>
            <div class="div-btn">
                <button class="btn-login">
                    ENTRAR
                </button>
                <?php
                if (!$_SERVER['HTTP_USER_AGENT'] == "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36") {
                ?>
                    <p class="text-center font-color"><small>entrar com</small></p>

                    <button class="btn-facebook">
                        <i class="fa-brands fa-facebook"></i> facebook
                    </button>
                <?php } ?>
            </div>

            <p class="mt-1 text-center"><a class="link-cadastre font-color" href="<?= $url ?>/cadastro">Não tem uma conta? <b class="blue">Cadastre-se</b></a></p>

        </div>
    </div>
</div>