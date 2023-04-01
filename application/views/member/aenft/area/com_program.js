export default Vue.component("ComProgram", {
	template: `
    <div class="cs-iconbox cs-style1 cs-white_bg">
        <page-loader :loading="loading" :fail="fail"></page-loader>
        <div v-if="!loading">
            <div class="overflow-hidden mb-1">
                <p class="font-weight-normal mb-0" style="font-size: 30px;"><strong class="font-weight-extra-bold">Complimentary program</strong></p>
                <div class="overflow-hidden mb-4 pb-3">
                    <p class="mb-0">Available for you and family</p>
                </div>
            </div>
            <div v-if="mode == 'list'" class="row">
                <div v-for="program in programs" :key="program.id" class="col-6 card card-bg card__shadow mb-2">
                    <div class="cs-cta cs-style3 cs-accent_bg text-center">
                        <h4>{{ program.name }}</h4>
                        <p class="px-2 mt-1 mb-3"><i class="fa fa-info-circle"></i> {{ program.held_on | formatDate }}</p>
                        <span class="badge card-header-bg2 cs-font_16 cs-font_10_sm">
                            Participant : <span class="fw-bold">99 Orang</span>
                        </span>
                        <p><span class="fw-bold">{{ program.description }}</span></p>
                        <button @click="openParticipationForm(program)" class="btn btn-primary"><i class="fa fa-sign-in"></i> Participate</button>
                    </div>
                </div>
            </div>
            <div v-if="mode == 'participation'" class="row">
                <div class="col-12">
                    <h5>
                        Program : {{ currentProgram.name }}
                        <button class="btn btn-primary ms-3" @click="participants.push({validation:{},name:'',contact:''})">
                            <i class="fa fa-plus-circle"></i> Add Participant
                        </button>
                    </h5>
                    <table class="table text-light">
                        <thead class="text-center">
                            <th>No</th>
                            <th>Name</th>
                            <th>Contact/Phone Number</th>
                            <th></th>
                        </thead>
                        <tbody v-if="participants.length > 0">
                            <tr v-for="(participant,indexItem) in participants" :key="participant.id">
                                <td>
                                    {{ indexItem + 1 }}
                                </td>
                                <td>
                                    <input type="text" v-model="participant.name" class="form-control" :class="{'is-invalid':participant.validation.name}"/>
                                    <div v-if="participant.validation.name" style="display:block" class="invalid-feedback">
                                        {{ participant.validation.name }}
									</div>
                                </td>
                                <td>
                                    <input type="text" v-model="participant.contact" class="form-control" :class="{'is-invalid':participant.validation.contact}"/>
                                    <div v-if="participant.validation.contact" style="display:block" class="invalid-feedback">
                                        {{ participant.validation.contact }}
									</div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" @click="deleteParticipant(indexItem,participant)"><i class="fa fa-trash"></i></button>
                                    <i v-if="!participant.id" data-placement="top" title="Not saved yet" class="fa-sharp fa-solid fa-triangle-exclamation"></i>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="4" class="text-center">You haven't added participants yet<td/>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-end">
                        <button @click="mode = 'list'" class="btn btn-secondary"><i class="fa fa-close"></i> Close</button>
                        <v-button @click="save" class="btn btn-primary" icon="fa fa-save">Save</v-button>
                    </div>
                </div>
            </div>
        </div>
    </div>`,
	data() {
		return {
			programs: [],
			loading: false,
			currentProgram: {},
			participants: [],
			mode: "list",
		};
	},
	created() {
		this.fetchProgram();
	},
	methods: {
		save(self) {
			self.toggleLoading();
			$.post(this.baseUrl + "save_com_participant", {
				program_id: this.currentProgram.id,
				participants: this.participants,
			})
				.done((res) => {
					if (res.status) {
						Swal.fire("Success", "Participant has been saved !", "success");
					} else {
						Swal.fire("Warning", res.message, "warning");
					}
					this.participants = res.participants;
				})
				.fail(() => {
					Swal.fire("Fail", "Failed to save participant !", "error");
				})
				.always(() => {
					self.toggleLoading();
				});
		},
		deleteParticipant(itemIndex, participant) {
			this.participants.splice(itemIndex, 1);
		},
		openParticipationForm(program) {
			this.currentProgram = program;
			this.mode = "participation";
		},
		fetchProgram() {
			this.loading = true;
			this.fail = false;
			$.post(this.baseUrl + "get_com_program", null, (res) => {
				this.programs = res.programs;
			})
				.fail(() => {
					this.fail = true;
				})
				.always(() => {
					this.loading = false;
				});
		},
	},
});
