<section class="page-header page-header-color page-header-quaternary page-header-more-padding custom-page-header" style="margin-bottom:0px">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Symposium & Workshops</h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="https://coe67-surakarta.com/">Home</a></li>
                    <li class="active">Symposium & Workshops </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding">
    <div class="container">
        <div class="row mb-3">
            <div class="col-lg-12">
                
                <h2 class="font-weight-bold text-color-dark">Scientific Events</h2>
                <?php
                    $colap = 1;
                    foreach ($query as $row):
                ?>
                <div class="accordion without-bg custom-accordion-style-1" id="accordion7">
                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title m-0">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion7" href="#colap<?php echo $colap;?>" aria-expanded="false">
                                    <?php echo $row->kategory ?> <span class="custom-accordion-plus"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="colap<?php echo $colap;?>" class="collapse" aria-expanded="false" style="height: 0px;">
                            <div class="card-body">
                                <p>
                            <?php
                                foreach ($row->acara as $row2):
                            ?>
                            <table  class="table table-bordered">
                            <tr>
                                <td rowspan="2">
                                    <?php echo $row2->nama_acara ?>
                                </td>    
                            
                            <?php
                                foreach ($row2->id_acara as $row3):
                            ?>
                                <td><?php echo $row3->jenis_harga ?> <br> (<?php echo $row3->waktu_berlaku ?> ) </td>
                            <?php
                                endforeach;
                            ?>
                            <td rowspan="2" align="center" class="align-middle"><a href="<?= base_url("site/login"); ?>" class="btn btn-success">ORDER</a></td>
                            </tr>
                            <tr>
                            <?php
                                foreach ($row2->id_acara as $row3):
                            ?>
                                <td><?php echo $row3->harga ?></td>
                            <?php
                                endforeach;
                            ?>
                            </tr>
                            </table>

                            <?php
                                endforeach;
                            ?>
                                </p>
                            </div>
                        </div>

                    </div>   
                </div>
                <?php
                    $colap++;
                    endforeach;
                ?>
                <br><br>

                <!-- <h2 class="font-weight-bold text-color-dark">Scientific</h2>

                <table  class="table table-bordered">
                        <?php
                            foreach ($query as $row):
                        ?>
                        <tr>
                            <th><?php echo $row->kategory ?></th>
                        </tr>
                            <?php
                                foreach ($row->acara as $row2):
                            ?>
                            <?php $rowspan=1; ?>
                            <tr>
                                <td rowspan="2">
                                    <?php echo $row2->nama_acara ?>
                                </td>    
                            
                            <?php
                                foreach ($row2->id_acara as $row3):
                            ?>
                                <td><?php echo $row3->jenis_harga ?> <br> (<?php echo $row3->waktu_berlaku ?> ) </td>
                            <?php
                                $rowspan++;
                                endforeach;
                            ?>
                            </tr>
                            <tr>
                            <?php
                                foreach ($row2->id_acara as $row3):
                            ?>
                                <td><?php echo $row3->harga ?></td>
                            <?php
                                endforeach;
                            ?>
                            </tr>
                            <?php
                                endforeach;
                            ?>
                        <?php
                            endforeach;
                        ?>
                </table> -->

            </div>
        </div>
    </div>
</section>