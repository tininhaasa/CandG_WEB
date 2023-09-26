<section class="my-properties">
    <div class="container">
        <input type="text" class="form-control search" name="search" id="search" placeholder="Pesquisar">

        <div class="filter">
            <a class="filter-type <?= $property_type == '1' || $property_type == '' ? 'active' : '' ?>" href="<?= $url ?>/ver/minhas-propriedades/1">
                <input type="radio" class="type-input" hidden name="type" id="inn" value="1">
                POUSADAS
            </a>
            <a class="filter-type <?= $property_type == '2' ? 'active' : '' ?>" href="<?= $url ?>/ver/minhas-propriedades/2">
                <input type="radio" class="type-input" hidden name="type" id="inn" value="2">
                BARCOS HOTEL
            </a>
            <a class="filter-type <?= $property_type == '3' ? 'active' : '' ?>" href="<?= $url ?>/ver/minhas-propriedades/3">
                <input type="radio" class="type-input" hidden name="type" id="rent" value="3">
                BARCO DE ALUGUEL
            </a>
            <a class="filter-type <?= $property_type == '4' ? 'active' : '' ?>" href="<?= $url ?>/ver/minhas-propriedades/4">
                <input type="radio" class="type-input" hidden name="type" id="fishpay" value="4">
                PESQUE PAGUE
            </a>
            <a class="filter-type <?= $property_type == '5' ? 'active' : '' ?>" href="<?= $url ?>/ver/minhas-propriedades/5">
                <input type="radio" class="type-input" hidden name="type" id="store" value="5">
                LOJAS
            </a>
            <a class="filter-type <?= $property_type == '6' ? 'active' : '' ?>" href="<?= $url ?>/ver/minhas-propriedades/6">
                <input type="radio" class="type-input" hidden name="type" id="transfer" value="6">
                TRANSFERS
            </a>


        </div>
        <div class="filter">
            <div class="filter-status <?= $status == '3' ? 'active' : '' ?>">
                <input type="radio" hidden name="status" data-type="" <?= $status == '3' ? 'checked' : '' ?> id="all" value="3">
                TODOS
            </div>
            <div class="filter-status <?= $status == '1' ? 'active' : ''; ?>">
                <input type="radio" hidden name="status" <?= $status == '1' ? 'checked' : '' ?> id="approve" value="1">
                APROVADOS
            </div>
            <div class="filter-status <?= $status == '0' ? 'active' : '' ?> ">
                <input type="radio" hidden name="status" id="waiting" <?= $status == '0' ? 'checked' : '' ?> value="0">
                EM ANALISE
            </div>
            <div class="filter-status <?= $status == '2' ? 'active' : '' ?>">
                <input type="radio" hidden name="status" data-type="" <?= $status == '2' ? 'checked' : '' ?> id="disaprove" value="2">
                REPROVADOS
            </div>

        </div>
        <div class="results">

            <?php foreach ($properties as $key => $property) { ?>
                <div class="box-product">

                    <div class="image-size">
                        <?php if($property["type_property"] == null): ?>
                            <div class="image-hotel" style="background-image: url(<?= $url ?>/assets/img/property/<?= mb_strtolower($table) ?>/<?= ($statuslink != '') ? $property['photo_id'] : $property['id'] ?>/<?= $property['image'] ?>);"></div>
                        <?php else: ?>
                            <div class="image-hotel" style="background-image: url(<?= $url ?>/assets/img/property/<?= mb_strtolower($table) ?>/<?=  $property['photo_id'] ?>/<?= $property['image'] ?>);"></div>
                        <?php endif; ?>
                    </div>

                    <div class="information-product for-traduction">
                        <h5 class="not-translate"><?= $property['name'] ?></h5>
                        <?php if (isset($property['bedrooms']) && isset($property['packs'])) : ?>
                            <p class="m-0"><span>Quartos:</span> <span><?= $property['bedrooms'] ?></span></p>
                            <p class="m-0">Pacotes: <?= $property['packs'] ?></p>
                        <?php endif; ?>
                        
                        <p class="m-0"><span>Status:</span><span>  <?= $propertyController->statusText($property['status']) ?></span></p>
                    
                        <?php if($property["status"] == 2): ?>
                            <p class="m-0"><span>Motivo: </span><span> <?= $property["justify"] ?></span></p>
                        <?php endif; ?>

                    </div>

                    <div class="more-options mt-3">
                        <?php if($property["type_property"] == null): ?>
                            <a class="edit" href="<?= $url ?>/editar/<?= $linkproperty ?><?= $linkproperty != 'pacote' ? $statuslink : '' ?>/<?= isset($property['all_inclusive_type']) ? $property['all_inclusive_type'] . "/" : "" ?><?= $property['id'] ?><?= $linkproperty == 'pacote' ? "$statuslink" : '' ?>">EDITAR</a>
                        <?php else: ?>
                            <?php if($linkproperty == "pacote"): ?>
                                <a class="edit" href="<?= $url ?>/editar/<?= $linkproperty ?>/<?=$property['all_inclusive_type']?>/<?= $property['id'] ?>/<?=$property["type_property"] == "official" ? "aprovado" : ""?>">EDITAR</a>
                            <?php else: ?>
                                <a class="edit" href="<?= $url ?>/editar/<?= $linkproperty ?>/<?=$property["type_property"] == "official" ? "aprovado/" : ""?><?= $property['id'] ?>">EDITAR</a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($property_type == 1 || $property_type == 2) { ?>
                            <a class="btn-bedroom" href="<?= $url ?>/cadastrar/quarto/<?= $property['id']; ?>">CADASTRAR QUARTO</a>
                            
                            <?php if((isset($property["photo_id"]) && !isset($property["type_property"])) || (isset($property["type_property"]) && $property["type_property"] == "official")): ?>
                                <a class="btn-bedroom" href="<?= $url ?>/cadastrar/pacote/<?= $property['type'] == 1 ? 'pousada' : 'barco-hotel' ?>/<?= $property['id']; ?>">CRIAR PACOTE</a>
                            <?php endif; ?>
                            
                            <?php 
                            if (isset($property['purchase_id']) && $property['purchase_id'] != '' && $property['purchase_status'] == "ACTIVE" && $property['status'] != '2') { ?>
                                <button class="btn-bedroom btn-notify" type="button" data-toggle="modal" data-target="#notify-modal" data-id="<?= $property['id'] ?>" data-type="<?= $property_type ?>">CRIAR NOTIFICAÇÃO</button>
                            <?php } ?>

                        <?php } ?>

                        <?php if (((!isset($property['purchase_id']) || $property['purchase_id'] == '') && $property_type != '7') || (isset($property['purchase_id']) && $property['purchase_id'] != '' && $property_type != '7' && $property['purchase_status'] == "APPROVAL_PENDING")) {?>
                            <a data-id="<?= ($property['photo_id'] != '') ? $property['photo_id'] : $property['id']; ?>" data-property="<?= $property_type ?>" class="btn-bedroom plans pointer openModal">VER PLANOS</a>
                            <p class="font-black plan-warning" style="color:red">Parece que você não completou o processo de assinatura de nenhum de nossos planos, para que sua propreiedade apareça em nosso site esse processo deve ser completado.</p>
                        <?php } else if ($property['purchase_status'] == 'SUSPENDED') {} ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if (count($properties) > 0) {
            $start = 0;
        ?>
            <nav aria-label="Page navigation example" style="position: relative!important;">
                <center>

                    <ul class="pagination justify-content-center my-3">

                        <?php if ($pagenumber >= 2) { ?>
                            <li class="page-item fist"><a class="page-link" href="<?= $url ?>/dashboard/<?= $page ?><?= $columnist ?>"><span> &laquo;</span></a></li>
                            <li class="page-item previous"><a class="page-link" href="<?= $url ?>/dashboard/<?= $page ?>/<?= $previous ?>"><span aria-hidden="true">&lsaquo;</span></a></li>
                        <?php } ?>

                        <?php
                        for ($i = $pagenumber - $pagination; $i <= $pagenumber - 1; $i++) {

                            if ($i > 0) {
                        ?>
                                <li class="page-item"><a class="page-link" href='<?= $url ?>/dashboard/<?= $page ?>/<?= $i ?>'>
                                        <div class='circle'><?= $i ?></div>
                                    </a>
                                </li>
                        <?php   }

                            $start = $start - $totalpages;
                        }
                        ?>

                        <?php
                        if ($totalnews > $limit) {
                        ?>
                            <li class="page-item">
                                <a class="page-link" href='<?= $url ?>/dashboard/<?= $page ?>/<?= $pagenumber ?>'>
                                    <div class='circle'><strong><?= $pagenumber ?></strong></div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php
                        for ($i = $pagenumber + 1; $i < $pagenumber + $pagination; $i++) {

                            if ($i <= $totalpages) {
                        ?>
                                <li class="page-item">
                                    <a class="page-link" href='<?= $url ?>/dashboard/<?= $page ?>/<?= $i ?>'>
                                        <div class='circle'><?= $i ?></div>
                                    </a>
                                </li>
                        <?php
                            }

                            $start = $start + $totalpages;
                        }
                        ?>

                        <?php
                        if ($pagenumber < $totalpages) { ?>

                            <li class="page-item next"><a class="page-link" href="<?= $url ?>/dashboard/<?= $page ?>/<?= $next ?>"><span aria-hidden="true">&rsaquo;</span></a></li>
                            <li class="page-item last"><a class="page-link" href="<?= $url ?>/dashboard/<?= $page ?>/<?= $totalpages ?>"><span>&raquo;</span></a></li>

                        <?php
                        }
                        ?>
                    </ul>
                </center>
            </nav>
        <?php } ?>
    </div>
</section>

<div class="modal fade" id="notify-modal" tabindex="-1" aria-labelledby="notify-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body plans-div">
                <form id="notify-form">
                    <div class="form-group">
                        <label for="notify-title">Título</label>
                        <input type="text" name="notify-title" class="form-control" id="notify-title">
                    </div>
                    <div class="form-group">
                        <label for="notify-message">Mensagem</label>
                        <textarea class="form-control" name="notify-message" id="notify-message" rows="5" style="resize: none;"></textarea>
                        <!-- <input type="text" class="form-control" id="message"> -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-send-notify">Enviar Notificações</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="plans-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body plans-div">
                <form id="form-payment">

                    <div class="step-1">
                        <p><b>Não se preocupe!</b> <span>você irá inserir seu cartão de crédito agora, mas a primeira cobrança da assinatura irá acontecer após o período de teste, se o plano selecionado tiver um.</span></p>
                        <?php if ($plans[0] != '') { ?>

                            <?php foreach ($plans as $key => $plan) {

                                if($plan["category"] == $property_type):

                                    $price = explode('.', $plan['price']); ?>

                                    <div class="plans pointer <?= $key == 0 ? 'active' : ''; ?>">
                                        <div class="plans-head">
                                            <h3 class="font-black"><span>ASSINATURA</span> <span><?= $plan['name'] ?></span></h3>
                                        </div>
                                        <div class="plans-body price-div">

                                            <p class="price-text font-black"><?= $price[0] ?><small class="font-black">,<?= $price[1] ?></small> </p>
                                            <div class="plans-description">
                                                <p style="font-size: 13px;padding: 15px 5px 5px 5px;text-align: justify;">
                                                <?= ($plan['highlight'] == '1') ? '<b>Com esse plano você irá aparecer entre os destaques na nossa página principal e aparecerá no topo das pesquisas</b><br>' : '' ; ?><?=  $plan["description"] ?></p>
                                            </div>

                                        </div>
                                        <input type="radio" <?= $key == 0 ? 'checked' : ''; ?> hidden id="plans" name="plans" data-free="<?= $plan['free'] ?>" value="<?= $plan['id'] ?>">
                                    </div>

                                <?php endif; ?>

                            <?php } ?> 
                            <div class="modal-footer">
                                <button type="button" class="btn-assignplan" data-property="<?=$property_type?>">Ir para o pagamento</button>
                            </div>
                        <?php
                        } else {
                        ?>
                        <h5 class="font-color font-bold text-center ">Pelo visto não há planos cadastrados no nosso sistema. Retorne em breve.</h3>
                        <?php } ?>
                    </div>
                    <div class="step-2" style="display:none">
                        <h5 class="font-black mb-1" style="text-transform:uppercase;">DADOS DO CARTÃO</h5>
                        <input type="text" hidden id="property-type" name="property-type">
                        <input type="text" hidden id="property-id" name="property-id">
                        <div class="form-group">
                            <label for="name-card">Nome impresso no cartão</label>
                            <input type="text" name="name-card" id="name-card" value="<?= (ENV == 'dev') ? "João Gomes" : "" ;?>" placeholder="Ex.: João" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="number-card">Número do cartão</label>
                            <input type="tel" name="number-card" id="number-card" value="<?= (ENV == 'dev') ? "5526 9491 4175 1190" : "" ;?>" placeholder="Ex.: 0000 0000 0000 0000" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="code-card">Código de segurança</label>
                            <input type="tel" name="code-card" id="code-card" value="<?= (ENV == 'dev') ? "348" : "" ;?>" placeholder="Ex.: 123" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="validate-card">Validade do cartão</label>
                            <input type="tel" name="validate-card" id="validate-card"  value="<?= (ENV == 'dev') ? "06/2023" : "" ;?>" placeholder="E.: 12/2021" class="form-control">
                        </div>

                        <h5 class="font-black mb-1" style="text-transform:uppercase;">Dados do titular:</h5>

                        <div class="row">

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>Nome:</label>
                                    <input type="text" class="form-control" name="card-name" id="value" value="<?php echo (ENV == 'dev') ? 'João da Silva' : ($this->helpers['UserSession']->get('name') ? $this->helpers['UserSession']->get('name') : ''); ?>" />
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>E-Mail:</label>
                                    <input type="text" class="form-control" name="card-email" id="value" placeholder="Ex. joao@gmail.com" value="<?php echo (ENV == 'dev') ? 'joao@gmail.com' : ($this->helpers['UserSession']->get('email') ? $this->helpers['UserSession']->get('email') : ''); ?>" />
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label>Documento:</label>
                                    <input type="tel" class="form-control" name="card-document" id="value" placeholder="Ex. 36715453055" value="<?php echo (ENV == 'dev') ? '36715453055' : ''; ?>" />
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label>Código Postal:</label>
                                    <input type="tel" class="form-control" name="card-cep" id="value" placeholder="Ex. 79910970" value="<?php echo (ENV == 'dev') ? '79910970' : ''; ?>" />
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label>Número:</label>
                                    <input type="tel" class="form-control" name="card-number-address" id="value" placeholder="Ex. 569" value="<?php echo (ENV == 'dev') ? '569' : ''; ?>" />
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mt-2">
                                <div class="form-group">
                                    <label>Telefone:</label>
                                    <input type="tel" class="form-control" name="card-phone" id="value" placeholder="Ex. (11) 99554-1254" value="<?php echo (ENV == 'dev') ? '(11) 99554-1254' : ''; ?>" />
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-selectplan" data-property="<?=$property_type?>">Voltar</button>
                            <button type="button" class="btn-pay" data-property="<?=$property_type?>">Finalizar Pagamento</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>