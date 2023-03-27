pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.js";//window.location.origin+"/assets/js/pdfjs/build/pdf.worker.js";
Vue.component("Pdfviewer",{
    template:`
    <div class="text-center row text-dark">
        <div class="col-12 mb-2">
            <div class="justify-content-center align-items-center btn-toolbar">
                <button v-if="currentPage > 1" @click="queueRenderPage(currentPage-1)" :class="[ btnClass ? btnClass : 'genric-btn info circle arrow medium']">
                    <i class="fa fa-chevron-left pb-2 pt-2"></i>
                </button>
                <span class="ml-1 mr-1">{{ currentPage }} dari {{ numberPages }} Halaman</span>
                <button v-if="currentPage < numberPages" @click="queueRenderPage(currentPage+1)" :class="[ btnClass ? btnClass : 'genric-btn info circle arrow medium']">
                    <i class="fa fa-chevron-right pb-2 pt-2"></i>
                </button>
                <a :href="downloadLink" target="_blank" class="btn btn-primary ms-3">Download</a>
            </div>
        </div>
        <div class="col-12">
            <canvas ref="pdfview" style="border: 1px solid;"></canvas>
        </div>
    </div>`,
    props:{
        downloadLink  :{
            type: String,
			default: ''
        },
        file:{
            type: String,
			default: ''
        },
        btnClass:{
            type:String,
            default:''
        }
    },
    data:() => {
        return {
            pdfDoc:null,
            baseUrl:window.location.origin,
            currentPage:1,
            numberPages:0,
            canvas:null,
            scale:1,
            ctx:null,
            pdfPages:[],
            pageRendering:false,
            pageNumPending:null,
        }
    },
    mounted(){
        this.canvas = this.$refs.pdfview;
        this.ctx = this.canvas.getContext("2d");
        let comp = this;
        if(this.file){
            let file = atob(this.file);
            var loadingTask = pdfjsLib.getDocument({
                data: file
            });
            loadingTask.promise.then((pdfDoc_) => {
                comp.pdfDoc = pdfDoc_;
                comp.numberPages = comp.pdfDoc.numPages;
                comp.renderPage(comp.currentPage,true);
            }, function(reason) {
                console.error(reason);
            });
        }
    },
    methods:{
        queueRenderPage(num) {
            if (this.pageRendering) {
                this.pageNumPending = num;
            } else {
                this.renderPage(num,false);
            }
        },
        renderPage(num,firstInit){
            this.pageRendering = true;
            this.pdfDoc.getPage(num).then((page) => {
                var viewport = page.getViewport({
                    scale:this.scale
                });

                this.canvas.width = viewport.width;//Math.floor(viewport.width * outputScale);
                this.canvas.height = viewport.height;//Math.floor(viewport.height * outputScale);
                this.canvas.style.width = viewport.width;//Math.floor(viewport.width * outputScale) + "px";
                this.canvas.style.height =  viewport.height;//Math.floor(viewport.height * outputScale) + "px";
                
                // Render PDF page into canvas context
                var renderContext = {
                    canvasContext: this.ctx,
                    viewport: viewport
                };
                
                var renderTask = page.render(renderContext);

               
                // Wait for rendering to finish
                renderTask.promise.then(() => {
                    this.pageRendering = false;
                    this.pdfPages[num] = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
                    if (this.pageNumPending !== null) {
                        this.renderPage(this.pageNumPending,false);
                        this.pageNumPending = null;
                    }
                });
            });
            this.currentPage = num;
        }
    }
});