<section class="page-header page-header-modern bg-color-quaternary page-header-md custom-page-header">
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-8 order-2 order-md-1 align-self-center p-static">
                <h1>Downloads</h1>
            </div>
            <div class="col-md-4 order-1 order-md-2 align-self-center">
                <ul class="breadcrumb d-block text-md-right breadcrumb-light">
                    <li><a href="<?=base_url('site/home');?>">Home</a></li>
                    <li class="active">Downloads</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="custom-section-padding">
    <div class="container">
    <div class="alert alert-info alert-dismissable alert-hotel">
        <i class="fa fa-info"></i>
        <b>Attention</b>
         To get more material, please login with 6 digits code on the ID card.
    </div>
    <div class="row">
        <div class="col-md-5">
                        <form id="defaultForm" method="post" class="form-horizontal" action="https://coe67-surakarta.com/frontend/read/login" >
                <div class="form-group row" style="display:">
                    <label class="col-lg-2 control-label">Code</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="code" />
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </div>
                
                <div class="form-group row" style="display:none">
                    <label class="col-lg-10">Welcome, </label>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-primary" onclick="window.location.href='https://coe67-surakarta.com/frontend/read/logout'">Logout</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-7">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>File Name</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                        <td style="padding: 4px;text-align: center;width: 10px;">1</td>
                        <td>Second Announcement</td>
                        <td style="padding: 4px;text-align: center;width: 60px;">
                            <button type="button" class="btn btn-primary" style="padding: 3px 10px; " 
                            onclick="window.location.href='https://coe67-surakarta.com/assets/frontend/download/8e0d334737435ee8aae19be9752ae3ae.pdf'">download</button>
                    
                        </td>
                    </tr>
                                        <tr>
                        <td style="padding: 4px;text-align: center;width: 10px;">2</td>
                        <td>Proceeding</td>
                        <td style="padding: 4px;text-align: center;width: 60px;">
                            <button type="button" class="btn btn-primary" style="padding: 3px 10px; " 
                            onclick="window.location.href='https://coe67-surakarta.com/assets/frontend/download/proceeding.pdf'">download</button>
                    
                        </td>
                    </tr>
                                        <tr>
                        <td style="padding: 4px;text-align: center;width: 10px;">3</td>
                        <td>Final Announcement</td>
                        <td style="padding: 4px;text-align: center;width: 60px;">
                            <button type="button" class="btn btn-primary" style="padding: 3px 10px; " 
                            onclick="window.location.href='https://coe67-surakarta.com/assets/frontend/download/e94dc722a33d57ec9d53d1a3082a4185.pdf'">download</button>
                    
                        </td>
                    </tr>
                                    </tbody>
            </table>
        </div>
    </div>
</div>
</section>
