Vue.component("VueUploadImage", {
	template: `
	<div class="card card-body">
		<div class="row">
			<div v-for="(file,index) in files" style="position:relative">
				<img style="max-height:180px" :src="convertLink(file)" class="img img-responsive img-thumbnail m-1" />
				<div style="position:absolute;bottom:15px;right:15px;">
				<button v-if="index > 0" type="button" @click="move(-1,index)"  class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i></button>
				<button v-if="index < files.length" type="button" @click="move(1,index)"  class="btn btn-sm btn-info"><i class="fa fa-arrow-right"></i></button>
				<button type="button" @click="deleteFile(file,index)"  class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
				</div>
			</div>
		</div>
		<input ref="inputFile" @change="onFileChange" type="file" multiple hidden/>
		<button type="button" @click="changeFile" :class="classBtn">
			Add File
		</button>
	</div>
`,
	name: "upload-image",
	props: {
		classBtn: {
			type: String,
			default: "btn btn-primary",
		},
		urlUpload: {
			type: String,
			required: true,
		},
		path: {
			type: String,
			required: true,
		},
		initialFiles: {
			type: String,
			default: [],
		},
	},
	data: function () {
		return {
			files: [],
		};
	},
	computed: {
		indexFirstFile() {
			return this.files.findIndex((item) => item instanceof File);
		},
	},
	mounted: function () {
		try {
			let files = JSON.parse(this.initialFiles);
			this.files = typeof files != "string" ? files : [];
		} catch {
			this.files = [];
		}
	},
	methods: {
		move(increment, currentIndex) {
			let newIndex = currentIndex + increment;
			if (newIndex >= 0) {
				let item = this.files[currentIndex];
				this.files.splice(currentIndex, 1);
				this.files.splice(newIndex, 0, item);
			}
			console.log(this.files);
		},
		startUpload(id) {
			return Promise.all(
				this.files.map(async (file, index) => {
					let formData = new FormData();
					formData.append("file", file);
					formData.append("index", index);
					formData.append("id", id);
					let response = await fetch(this.urlUpload, {
						method: "POST",
						body: formData,
					});
					let dataResponse = await response.json();
					if (dataResponse.status) {
						Vue.set(this.files, index, dataResponse.data.path);
					}
					return dataResponse;
				})
			);
		},
		setItem(file, index) {
			if (index) {
				Vue.set(this.files, index, file);
			} else {
				this.files.push(file);
			}
		},
		deleteFile(file, index) {
			if (file instanceof File) {
				this.files.splice(index, 1);
			}
		},
		convertLink(file) {
			if (file instanceof File) {
				return URL.createObjectURL(file);
			} else if (typeof file == "string") {
				return this.path + "/" + file;
			}
		},
		changeFile() {
			this.$refs.inputFile.click();
		},
		onFileChange(event) {
			if (event.target.files.length > 0) {
				for (var i = 0; i < event.target.files.length; i++) {
					this.files.push(event.target.files.item(i));
				}
			}
		},
	},
});
