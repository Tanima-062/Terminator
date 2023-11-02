<template>
  <div class="content bg-white rounded-[15px] shadow-container mx-6 px-[43px] pt-[42px] pb-[62px] min-h-[89vh]">
    <Back class="mb-[31px]" />
    <Tabs />
    <div class="contents-wrapper flex gap-[34px] flex-col">
      <template v-if="Object.keys(contact_data_record).length">
        <DuplicateCheckComponent
          :duplicates="contact_data_record.duplicates"
          v-if="contact_data_record.duplicates.length && (contact_data_record.contact_record_status == 'Check Duplicate' || contact_data_record.contact_record_status == 'Duplicate')"
          :contact_data_record_id="contact_data_record.id"
          @updateContactDataRecord="getContactRecordData(vRoute.params.id)"
        />

        <Accordion title="General contact record information" bodyClass="grid grid-cols-3 gap-y-11 !px-[30px]">
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('ID') }}</h4>
            <p class="text-16 text-[#707070]">{{ contact_data_record.prefix_id }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Source') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.source ?? '') }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Category') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.category ?? '') }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Campaign') }}</h4>
            <p class="text-16 text-[#707070]">{{ contact_data_record.campaign.name }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Creation Date') }}</h4>
            <p class="text-16 text-[#707070]" v-date-format="contact_data_record.created_at"></p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Creator') }}</h4>
            <p class="text-16 text-[#707070]">{{ contact_data_record.creator.full_name }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Allocated to') }}</h4>
            <p class="text-16 text-[#707070]">{{ contact_data_record.allocation?.assigned_broker?.name }}</p>
          </div>

          <div class="content flex flex-col gap-3" v-if="contact_data_record.allocation">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ contact_data_record.allocation.assigned_organization_element?.hierarchy_type.name }}</h4>
            <p class="text-16 text-[#707070]">{{ contact_data_record.allocation.assigned_organization_element?.name }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Last Feedback') }}</h4>
            <p class="text-16 text-[#707070]">{{ contact_data_record.last_feedback?.feedback }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Feedback Time') }}</h4>
            <p class="text-16 text-[#707070]" v-date-format:datetime="contact_data_record.last_feedback?.created_at"></p>
          </div>

          <div class="content flex flex-col gap-3" v-if="!hasPermission('quality_check')">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Control status (Lead)') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.lead ?? '') }}</p>
          </div>

          <div class="content flex flex-col gap-3" v-if="!hasPermission('quality_check')">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Remarks Control (Lead)') }}</h4>
            <p class="text-16 text-[#707070]">{{ contact_data_record.remarks_control_lead }}</p>
          </div>

          <div class="col-span-3" v-if="hasPermission('process_lead_again')"></div>
          <div class="content flex flex-col gap-5 w-[331px] bg-[#F1F1F1] p-[14px]" v-if="hasPermission('process_lead_again')">
            <div class="flex gap-[10px] items-center">
              <Checkbox :checked="workflow_form.data_verified_updated" @click="workflow_form.data_verified_updated = !workflow_form.data_verified_updated" :width="17" :height="17" />
              <label class="text-[#B6316A] text-[20px] leading-6 font-bold">{{ $t('Data checked/updated') }}</label>
            </div>

            <ButtonGradient v-if="!workflow_form.data_verified_updated" class="w-[187px] flex items-center justify-center" @click="notReached">
              {{ $t('Not reached') }}
            </ButtonGradient>
          </div>

          <div class="content flex flex-col gap-3 w-[331px] bg-[#F1F1F1] p-[14px]" v-if="hasPermission('quality_check')">
            <SingleSelect
              @onUpdate="vWorkflow$.lead.$touch()"
              :error="vWorkflow$.lead.$errors.length > 0"
              label="Control status (Lead)"
              placeholder="Select control status (lead)"
              :asterisk="true"
              :options="lead_lists"
              v-model="workflow_form.lead"
            />
          </div>

          <div class="content flex flex-col gap-3" v-if="hasPermission('quality_check')">
            <TextArea
              @input="vWorkflow$.remarks_control_lead.$touch()"
              :error="vWorkflow$.remarks_control_lead.$errors.length > 0"
              label="Remarks Control (Lead)"
              placeholder="Enter remarks"
              class="resize-none w-[290px]"
              v-model="workflow_form.remarks_control_lead"
            />
          </div>

          <template v-if="(workflow_form.lead == 'Not confirmed' || workflow_form.lead == 'Back to Call Agent') && hasPermission('quality_check')">
            <div class="content flex flex-col gap-3">
              <TextArea
                @input="vWorkflow$.lead_control_task.$touch()"
                :error="vWorkflow$.lead_control_task.$errors.length > 0"
                label="Lead Control Task"
                placeholder="Enter lead control task"
                class="resize-none w-[290px]"
                v-model="workflow_form.lead_control_task"
              />
            </div>
          </template>

          <template v-if="hasPermission('status_not_confirmed')">
            <div class="spacer col-span-3"></div>
            <div class="flex flex-col gap-3" v-for="contact_data_record_lead_control_task in contact_data_record.lead_control_tasks.filter(({ lead_control_task }) => lead_control_task)">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Lead Control Task') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record_lead_control_task.lead_control_task }}</p>
            </div>

            <TextArea
              @input="vWorkflow$.lead_control_task_remarks.$touch()"
              :error="vWorkflow$.lead_control_task_remarks.$errors.length > 0"
              label="Task Remarks"
              placeholder="Enter remarks"
              class="resize-none w-[290px]"
              v-model="workflow_form.lead_control_task_remarks"
            />

            <div class="col-span-3 gap-[10px] flex items-center">
              <Checkbox :checked="workflow_form.lead_control_task_status" @click="workflow_form.lead_control_task_status = !workflow_form.lead_control_task_status" :width="17" :height="17" />
              <label class="text-[#B6316A] text-[20px] leading-6">{{ $t('Edited') }}</label>
            </div>
          </template>

          <div class="content col-span-3 grid grid-cols-3 bg-[#F1F1F1] p-[14px]" v-if="hasPermission('arrange_appointment_call')">
            <SingleSelect
              @onUpdate="vWorkflow$.feedback.$touch()"
              :error="vWorkflow$.feedback.$errors.length > 0"
              label="Feedback"
              placeholder="Select feedback"
              :options="options.feedback_lists"
              v-model="workflow_form.feedback"
              class="w-[290px]"
              labelClass="text-[#B6316A]"
            />
            <TextArea
              @input="vWorkflow$.feedback_remarks.$touch()"
              :error="vWorkflow$.feedback_remarks.$errors.length > 0"
              label="Feedback remarks"
              placeholder="Enter remarks"
              class="resize-none w-[290px]"
              v-model="workflow_form.feedback_remarks"
              labelClass="text-[#B6316A]"
            />
            <SingleSelect
              label="Residential address confirmed"
              placeholder="Residential address confirmed select"
              :options="options.residential_address_confirmed"
              v-model="workflow_form.residential_address_confirmed"
              class="w-[290px]"
              labelClass="text-[#B6316A]"
            />
          </div>

          <div class="content col-span-3 grid grid-cols-3" v-if="hasPermission('arrange_appointment_call') && workflow_form.feedback == 'Call later'">
            <SingleDatePicker
              @onUpdate="vWorkflow$.call_date.$touch()"
              @error="vWorkflow$.call_date.$errors.length > 0"
              class="w-[290px]"
              label="Call date"
              v-model="workflow_form.call_date"
              :min_date="new Date(dayjs().add(1, 'day'))"
              :highlight_today="false"
            />

            <div class="w-[290px]">
              <label for="" class="text-input block mb-3 text-formLabel">{{ $t('Call time') }}</label>
              <input
                @input="vWorkflow$.call_time.$touch()"
                :error="vWorkflow$.call_time.$errors.length > 0"
                type="text"
                class="border border-input h-10 rounded-lg outline-none p-3 text-input text-16 w-full"
                v-model="workflow_form.call_time"
                placeholder="HH:MM"
                :class="{ 'border-error': vWorkflow$.call_time.$errors.length }"
              />
            </div>
          </div>

          <div class="content col-span-3 grid grid-cols-3" v-if="hasPermission('perform_quality_check')">
            <SingleSelect
              @onUpdate="vWorkflow$.control_status_appointment.$touch()"
              @error="vWorkflow$.control_status_appointment.$errors.length > 0"
              label="Control status (Appointment)"
              placeholder="Select control status (appointment)"
              :options="options.control_status_appointment_lists"
              v-model="workflow_form.control_status_appointment"
              class="w-[290px]"
              labelClass="text-[#B6316A]"
            />
            <TextArea
              @input="vWorkflow$.remarks_control_appointment.$touch()"
              :error="vWorkflow$.remarks_control_appointment.$errors.length > 0"
              label="Notes Control (Appointment)"
              placeholder="Enter remarks"
              class="resize-none w-[290px]"
              v-model="workflow_form.remarks_control_appointment"
              labelClass="text-[#B6316A]"
            />

            <div class="tasl flex flex-col gap-3" v-if="workflow_form.control_status_appointment == 'Back to call agent'">
              <TextArea
                @input="vWorkflow$.appointment_control_task.$touch()"
                :error="vWorkflow$.appointment_control_task.$errors.length > 0"
                label="Appointment Control Task"
                placeholder="Enter appointment control task"
                class="resize-none w-[290px]"
                v-model="workflow_form.appointment_control_task"
                labelClass="text-[#B6316A]"
              />
            </div>
          </div>

          <template v-if="hasPermission('process_quality_topic')">
            <div class="spacer col-span-3"></div>
            <div class="content bg-[#F1F1F1] w-[331px] p-[14px]">
              <SingleSelect
                @onUpdate="vWorkflow$.feedback.$touch()"
                @error="vWorkflow$.feedback.$errors.length > 0"
                label="Feedback"
                placeholder="Select feedback"
                :options="options.feedback_lists"
                v-model="workflow_form.feedback"
                class="w-[290px]"
                labelClass="text-[#B6316A]"
              />
            </div>
            <div class="flex flex-col gap-3" v-for="appoinment in contact_data_record.appointments.filter(({ appointment_control_task }) => appointment_control_task)">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Appointment Control Task') }}</h4>
              <p class="text-16 text-[#707070]">{{ appoinment.appointment_control_task }}</p>
            </div>

            <TextArea
              @input="vWorkflow$.appointment_control_task_remarks.$touch()"
              :error="vWorkflow$.appointment_control_task_remarks.$errors.length > 0"
              label="Task Remarks"
              placeholder="Enter remarks"
              class="resize-none w-[290px]"
              v-model="workflow_form.appointment_control_task_remarks"
            />

            <div class="col-span-3 gap-[10px] flex items-center">
              <Checkbox :checked="workflow_form.appointment_control_task_status" @click="workflow_form.appointment_control_task_status = !workflow_form.appointment_control_task_status" :width="17" :height="17" />
              <label class="text-[#B6316A] text-[20px] leading-6">{{ $t('Edited') }}</label>
            </div>
          </template>

          <template v-if="hasPermission('appointment_reminder_call')">
            <div class="flex flex-col gap-3">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Control status (Appointment)') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record.appointments[0].control_status_appointment }}</p>
            </div>

            <div class="flex flex-col gap-3">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Notes Control (Appointment)') }}</h4>
              <p class="text-16 text-[#707070]">{{ contact_data_record.appointments[0].notes }}</p>
            </div>

            <SingleSelect
              @onUpdate="vWorkflow$.appointment_reminder_status.$touch()"
              @error="vWorkflow$.appointment_reminder_status.$errors.length > 0"
              label="Control status (Appointment)"
              placeholder="Select control status (appointment)"
              :options="options.appointment_reminder_status_lists"
              v-model="workflow_form.appointment_reminder_status"
              class="w-[290px]"
              labelClass="text-[#B6316A]"
            />

            <TextArea
              @input="vWorkflow$.appointment_reminder_remarks.$touch()"
              :error="vWorkflow$.appointment_reminder_remarks.$errors.length > 0"
              label="Remarks appointment reminder"
              placeholder="Enter remarks"
              class="resize-none w-[290px]"
              v-model="workflow_form.appointment_reminder_remarks"
              labelClass="text-[#B6316A]"
            />

            <div class="flex flex-col gap-3">
              <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Appointment reminder date') }}</h4>
              <p class="text-16 text-[#707070]">{{ dayjs(`${contact_data_record.appointments[0].appointment_date} ${contact_data_record.appointments[0].appointment_time}`).subtract(1, 'day').format('DD.MM.YYYY') }}</p>
            </div>
          </template>

          <template v-if="hasPermission('intermediary_feedback_capture')">
            <div class="appointment-took-place flex flex-col gap-[10px]">
              <label class="text-input block mb-3 text-formLabel">{{ $t('Appointment took place?') }}</label>
              <div class="flex gap-10">
                <div class="inline-flex gap-[10px] items-center text-16 font-inter text-input">
                  <RadioInput :active="workflow_form.appointment_took_place === true" @click="workflow_form.appointment_took_place = true" />
                  {{ $t('Yes') }}
                </div>
                <div class="inline-flex gap-[10px] items-center text-16 font-inter text-input">
                  <RadioInput :active="workflow_form.appointment_took_place === false" @click="workflow_form.appointment_took_place = false" />
                  {{ $t('No') }}
                </div>
              </div>
            </div>

            <div class="appointment-took-place flex flex-col gap-[10px]" v-if="workflow_form.appointment_took_place === true">
              <label class="text-input block mb-3 text-formLabel">{{ $t('Appointment took place?') }}</label>
              <div class="flex flex-col gap-2">
                <div class="inline-flex gap-[10px] items-center text-16 font-inter text-input">
                  <RadioInput :active="workflow_form.outcome === 'Positive'" @click="workflow_form.outcome = 'Positive'" />
                  {{ $t('Positive') }}
                </div>
                <div class="inline-flex gap-[10px] items-center text-16 font-inter text-input">
                  <RadioInput :active="workflow_form.outcome === 'Negative'" @click="workflow_form.outcome = 'Negative'" />
                  {{ $t('Negative') }}
                </div>
                <div class="inline-flex gap-[10px] items-center text-16 font-inter text-input">
                  <RadioInput :active="workflow_form.outcome === 'Follow up contact necessary'" @click="workflow_form.outcome = 'Follow up contact necessary'" />
                  {{ $t('Follow up contact necessary') }}
                </div>
              </div>
            </div>
            <SingleSelect
              @onUpdate="
                () => {
                  workflow_form.errors.clear('reason')
                  vWorkflow$.reason.$touch()
                }
              "
              :error="vWorkflow$.reason.$errors.length > 0 || workflow_form.errors.has('reason')"
              label="Reason"
              placeholder="Select reason"
              :options="reason_options"
              v-model="workflow_form.reason"
              class="w-[290px]"
              v-if="workflow_form.appointment_took_place === false || (workflow_form.outcome == 'Negative' && workflow_form.appointment_took_place === true)"
            />

            <TextInput
              class="max-w-[290px]"
              label="Contracts concluded"
              placeholder="Contracts concluded enter"
              :error="vWorkflow$.contracts_concluded.$errors.length > 0"
              v-model="workflow_form.contracts_concluded"
              @input="vWorkflow$.contracts_concluded.$touch"
              v-if="workflow_form.outcome == 'Positive' && workflow_form.appointment_took_place == true"
            />

            <TextArea
              v-if="workflow_form.reason == 'Other' && workflow_form.outcome == 'Negative'"
              @input="vWorkflow$.other.$touch()"
              :error="vWorkflow$.other.$errors.length > 0"
              label="Other"
              placeholder="Please enter reason"
              class="resize-none w-[290px]"
              v-model="workflow_form.other"
            />

            <SingleDatePicker
              @onUpdate="vWorkflow$.expiry_year.$touch()"
              :error="vWorkflow$.expiry_year.$errors.length > 0"
              class="w-[290px]"
              label="Expiry Year"
              v-model="workflow_form.expiry_year"
              :highlight_today="false"
              v-if="workflow_form.appointment_took_place === true && workflow_form.outcome == 'Negative' && workflow_form.reason == 'Multi-year contract'"
            />

            <SingleDatePicker
              @onUpdate="vWorkflow$.follow_up_contact_date.$touch()"
              :error="vWorkflow$.follow_up_contact_date.$errors.length > 0"
              class="w-[290px]"
              label="Follow up contact date"
              v-model="workflow_form.follow_up_contact_date"
              :highlight_today="false"
              v-if="workflow_form.outcome == 'Follow up contact necessary' && workflow_form.appointment_took_place === true"
            />

            <div class="w-[290px]" v-if="workflow_form.outcome == 'Follow up contact necessary' && workflow_form.appointment_took_place === true">
              <label for="" class="text-input block mb-3 text-formLabel">{{ $t('Follow up contact time') }}</label>
              <input
                @input="vWorkflow$.follow_up_contact_time.$touch()"
                :error="vWorkflow$.follow_up_contact_time.$errors.length > 0"
                type="text"
                class="border border-input h-10 rounded-lg outline-none p-3 text-input text-16 w-full"
                v-model="workflow_form.follow_up_contact_time"
                placeholder="HH:MM"
                :class="{ 'border-error': vWorkflow$.follow_up_contact_time.$errors.length }"
              />
            </div>

            <TextArea
              @input="vWorkflow$.intermediary_remarks.$touch()"
              :error="vWorkflow$.intermediary_remarks.$errors.length > 0"
              label="Intermediary remarks"
              placeholder="Enter mediator remarks"
              class="resize-none w-[290px]"
              v-model="workflow_form.intermediary_remarks"
            />
          </template>
        </Accordion>

        <Accordion title="Person Information" bodyClass="grid grid-cols-[repeat(3,_290px)] gap-y-11 gap-x-[183px] !px-[30px]">
          <SingleSelect label="Source" placeholder="Select Source" :asterisk="true" :options="options.source_lists" v-model="form.source" />

          <TextInput label="First Name" :error="v$.first_name.$errors[0]?.$message" v-model="form.first_name" />

          <TextInput label="Last Name" :error="v$.last_name.$errors[0]?.$message" v-model="form.last_name" />

          <SingleDatePicker v-model="form.date_of_birth" label="Date of Birth" :asterisk="true" :error="v$.date_of_birth.$errors[0]?.$message" />

          <PhoneNumberInput
            label="Mobile Phone Number"
            v-model:country_code="form.phone_number_iso_code"
            v-model:phone_number="form.phone_number"
            class="w-full"
            :asterisk="true"
            :error="v$.phone_number_iso_code.$errors.length || v$.phone_number.$errors.length ? true : false"
          />

          <TextInput
            label="Email"
            :placeholder="'Enter e-mail'"
            :asterisk="true"
            v-model="form.email"
            class="w-full"
            @input="
              () => {
                v$.email.$touch()
              }
            "
            :error="v$.email.$errors[0]?.$message"
          />
          <div class="flex gap-3 flex-col">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Correspondence Language') }}</h4>
            <p class="text-16 text-[#707070]">{{ useLanguageCodeToName(contact_data_record.correspondence_language) }}</p>
          </div>
          <div class="flex gap-3 flex-col">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Other Languages') }}</h4>
            <p class="text-16 text-[#707070]">{{ contact_data_record.other_languages?.map((code) => useLanguageCodeToName(code)).join(', ') }}</p>
          </div>
        </Accordion>

        <Accordion title="Address" bodyClass="grid grid-cols-[repeat(3,_290px)] gap-y-11 gap-x-[183px] !px-[30px]">
          <TextInput
            label="Street"
            placeholder="Enter street"
            :asterisk="true"
            maxLength="31"
            v-model="form.street"
            class="w-full"
            :error="v$.street.$errors[0]?.$message"
            @input="
              () => {
                v$.street.$touch()
              }
            "
          />
          <TextInput
            label="House number"
            placeholder="Enter house number"
            :asterisk="true"
            maxLength="31"
            v-model="form.house_number"
            class="w-full"
            :error="v$.house_number.$errors[0]?.$message"
            @input="
              () => {
                v$.house_number.$touch()
              }
            "
          />
          <TextInput
            label="Zip Code"
            placeholder="Enter zip code"
            :asterisk="true"
            v-model="form.zip_code"
            class="w-full"
            :error="v$.zip_code.$errors[0]?.$message"
            @input="
              () => {
                v$.zip_code.$touch()
              }
            "
          />
          <TextInput
            label="City"
            placeholder="Enter city"
            :asterisk="true"
            maxLength="31"
            v-model="form.city"
            class="w-full"
            :error="v$.city.$errors[0]?.$message"
            @input="
              () => {
                v$.city.$touch()
              }
            "
          />
          <CountrySelect label="Country" placeholder="Select Country" v-model="form.country_iso_code" :asterisk="true" class="w-full" :error="v$.country_iso_code.$errors[0]?.$message" />
          <SingleSelect
            label="Canton"
            placeholder="Select canton"
            :asterisk="true"
            :options="options.canton_lists"
            :searchable="true"
            :searchables="['label']"
            v-model="form.canton"
            class="w-full"
            :error="v$.canton.$errors[0]?.$message"
          />

          <SingleSelect label="Region" placeholder="Select region" :asterisk="true" :options="options.region_lists" v-model="form.region" class="w-full" :error="v$.region.$errors[0]?.$message" />
        </Accordion>

        <Accordion title="Appointment relevant Information" bodyClass="grid grid-cols-[repeat(3,_290px)] gap-y-11 gap-x-[183px] !px-[30px]">
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Car Insurance') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.car_insurance ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('3rd Pillar') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.third_piller ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Household Goods') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.household_goods ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Legal Protection') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.legal_protection ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Health Status') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.health_status ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Health Insurance') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.health_insurance ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Contact person for insurance questions') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.contact_person_for_insurance_questions ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Last Health Insurance Change') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.street ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Satisfaction') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.satisfaction ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Saving') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.save ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Number of persons in household') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.number_of_persons_in_household?.toString() ?? '') }}</p>
          </div>
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Work Activity') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.work_activity ?? '') }}</p>
          </div>
        </Accordion>

        <Accordion title="Appointment relevant Information" bodyClass="grid grid-cols-[repeat(3,_290px)] gap-y-11 gap-x-[183px] !px-[30px]">
          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input">{{ $t('Availability') }}</h4>
            <div class="flex gap-[10px] items-center">
              <Checkbox :checked="!contact_data_record.availability_count > 0" width="17" height="17" /> <span class="text-[#636363] text-16 leading-[19px] font-roboto">{{ $t('No information') }}</span>
            </div>
          </div>

          <Availabilities v-if="contact_data_record.availability_count > 0" :availability="contact_data_record.availability" class="col-span-3" />

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Desired consulting channel') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.work_activity ?? '') }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Competition') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.competition ?? '') }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Origin link') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.origin_link ?? '') }}</p>
          </div>

          <div class="content flex flex-col gap-3">
            <h4 class="text-[18x] leading-[22px] font-semibold text-input whitespace-nowrap">{{ $t('Desired contact') }}</h4>
            <p class="text-16 text-[#707070]">{{ $t(contact_data_record.contact_desired ?? '') }}</p>
          </div>
        </Accordion>

        <Accordion title="Appointment relevant Information" bodyClass="flex flex-col gap-y-6 !px-[30px]">
          <AppointmentTable :contact_data_record="contact_data_record" v-model="appointments" />
        </Accordion>

        <div class="btns flex mt-[66px] gap-5">
          <ButtonGradient class="w-[255px] flex items-center justify-center gap-[10px]" type="submit" @click="continueWorkflow" v-if="hasPermissionToContinueWorkflow">
            <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M1.45651 0.52612C1.08375 0.603327 0.703962 0.9297 0.573848 1.28415C0.5 1.47716 0.5 1.50524 0.5 8.00111C0.5 14.497 0.5 14.5251 0.573848 14.7181C0.672313 14.9883 0.911441 15.2375 1.19628 15.3778C1.40728 15.4831 1.46003 15.4936 1.7308 15.4901C1.94883 15.4866 2.07191 15.4691 2.18796 15.4164C2.27588 15.3778 3.63679 14.325 5.21222 13.0757L8.07824 10.8016L8.09582 12.6932C8.10989 14.3777 8.12044 14.5952 8.17319 14.7146C8.46858 15.3603 9.1719 15.6621 9.8084 15.4129C10.0088 15.3322 17.8157 9.14166 18.1216 8.8188C18.4135 8.50646 18.4803 8.3696 18.4979 8.02918C18.5155 7.66421 18.4276 7.4747 18.0794 7.13429C17.7348 6.79739 10.0194 0.680533 9.8295 0.592798C9.35125 0.371707 8.662 0.561214 8.34902 0.996378C8.10286 1.33679 8.11692 1.22449 8.09582 3.30906L8.07824 5.20062L5.18058 2.90197C3.10227 1.25607 2.24071 0.592798 2.13873 0.561214C1.94532 0.501554 1.63937 0.487517 1.45651 0.52612Z"
                fill="white"
              />
            </svg>
            <span>
              {{ $t('Continue workflow') }}
            </span>
          </ButtonGradient>

          <ButtonGradient class="w-[187px] flex items-center justify-center" type="submit" @click="submit" :disabled="!useIsEqual(workflow_form.data(), workflow_form.originalData)">
            {{ $t('Save') }}
          </ButtonGradient>

          <ButtonWhite
            class="w-[187px]"
            type="button"
            @click="
              $vfm.show('redirect-modal', {
                title: 'Discard changes?',
                description: `If you go back or cancel without saving, all changes will be discarded. Are you sure you really want to discard the changes?`,
              })
            "
          >
            {{ $t('Cancel') }}
          </ButtonWhite>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router'
import { onMounted, reactive, computed, inject } from 'vue'
import ButtonGradient from '@/components/button/Gradient.vue'
import ButtonWhite from '@/components/button/White.vue'
import Tabs from './components/Tabs.vue'
import Back from '@/components/form/Back.vue'
import { useLanguageCodeToName, useGetCountryName } from '@/composables/translation.js'
import Checkbox from '@/components/utils/Checkbox.vue'
import { Form as vForm } from 'vform'
import axios from 'axios'
import DuplicateCheckComponent from './components/DuplicateCheckComponent.vue'
import Accordion from '@/components/utils/Accordion.vue'
import SingleSelect from '@/components/form/SingleSelect.vue'
import TextInput from '@/components/form/TextInput.vue'
import TextArea from '@/components/form/TextareaInput.vue'
import SingleDatePicker from '@/components/form/SingleDatePicker.vue'
import PhoneNumberInput from '@/components/form/PhoneNumberInput.vue'
import { helpers, required, maxLength, email, requiredIf, integer } from '@vuelidate/validators'
import CountrySelect from '@/components/form/CountrySelect.vue'
import { trans } from 'laravel-vue-i18n'
import useVuelidate from '@vuelidate/core'
import AppointmentTable from './components/AppointmentTable.vue'
import { useIsEqual } from '@/composables/utils.js'
import { useUserStore } from '@/store/user'
import RadioInput from '@/components/icons/RadioInput.vue'
import dayjs from 'dayjs'
import Availabilities from './components/Availabilities.vue'

const vRoute = useRoute()
const $vfm = inject('$vfm')
const router = useRouter()

const contact_data_record = reactive({})
const userStore = useUserStore()

const workflow_form = reactive(
  new vForm({
    lead: '',
    data_verified_updated: '',
    remarks_control_lead: '',
    lead_control_task_status: '',
    lead_control_task_remarks: '',
    lead_control_task: '',
    feedback: '',
    feedback_remarks: '',
    call_date: '',
    call_time: '',
    control_status_appointment: '',
    remarks_control_appointment: '',
    appointment_control_task: '',
    appointment_control_task_status: '',
    appointment_control_task_remarks: '',
    appointment_reminder_status: '',
    appointment_reminder_remarks: '',
    appointment_took_place: '',
    reason: '',
    outcome: '',
    contracts_concluded: '',
    other: '',
    expiry_year: '',
    follow_up_contact_date: '',
    follow_up_contact_time: '',
    intermediary_remarks: '',
  })
)

const form = reactive(
  new vForm({
    source: 'not_online',
    category: 'lead',
    campaign_id: 1,
    correspondence_language: null,
    other_language: [],
    salutation: null,
    first_name: null,
    last_name: null,
    date_of_birth: '',
    phone_number_iso_code: null,
    phone_number: null,
    email: null,
    street: null,
    house_number: null,
    zip_code: null,
    city: null,
    country_iso_code: null,
    canton: null,
    region: null,
    car_insurance: null,
    third_piller: null,
    household_goods: null,
    legal_protection: null,
    health_status: null,
    health_insurance: null,
    contact_person_for_insurance_questions: null,
    save: null,
    last_health_insurance_change: null,
    satisfaction: null,
    number_of_persons_in_household: null,
    work_activity: null,
    desired_consultation_channel: null,
    contact_desired: null,
    remarks_control_lead: null,
  })
)
const options = reactive({
  source_lists: [],
  category_lists: [],
  campaign_lists: [],
  salutation_lists: [],
  canton_lists: [],
  region_lists: [],
  car_insurance_lists: [],
  third_piller_lists: [],
  household_good_lists: [],
  legal_protection_lists: [],
  health_status_lists: [],
  health_insurance_lists: [],
  contact_person_for_insurance_question_lists: [],
  save_lists: [],
  last_health_insurance_change_lists: [],
  satisfaction_lists: [],
  number_of_persons_in_household_lists: [],
  work_activity_lists: [],
  desired_consultation_channel_lists: [],
  contact_desired_lists: [],
  lead_lists: [],
  appointment_reminder_status_lists: [
    { value: 'Done', label: 'Done' },
    { value: 'Not reached - Appointment reminder', label: 'Not reached' },
    { value: 'Cancelled', label: 'Cancelled' },
  ],
  control_status_appointment_lists: [
    { value: 'Confirmed', label: 'Confirmed' },
    { value: 'Back to call agent', label: 'Back to call agent' },
  ],
  residential_address_confirmed: [
    { value: true, label: 'Yes' },
    { value: false, label: 'No' },
  ],
  feedback_lists: [
    { value: 'Not Reached', label: 'Not Reached' },
    { value: 'Wrong Number', label: 'Wrong Number' },
    { value: 'No Interest', label: 'No Interest' },
    { value: 'Sick', label: 'Sick' },
    { value: 'Already terminated', label: 'Already terminated' },
    { value: 'Other Offer received', label: 'Other Offer received' },
    { value: 'Call later', label: 'Call later' },
    { value: 'Appointment', label: 'Appointment' },
    { value: 'No Potential', label: 'No Potential' },
  ],
})

const appointments = computed({
  get: () => contact_data_record.appointments ?? [],
  set: (value) => (contact_data_record.appointments = value),
})

const lead_lists = computed(() => {
  if (contact_data_record.contact_record_status == 'New' || contact_data_record.contact_record_status == 'Completed') {
    return options.lead_lists.filter(({ value }) => ['No Potential', 'Future Lead', 'Life Insurance', 'Car Insurance', 'Health Insurance', 'Not confirmed', 'Back to Call Agent'].includes(value))
  }
})

const reason_options = computed(() => {
  const appointment_did_not_took_place_resons = ['Not at home', 'Untraceable', 'Not reachable', 'Cancellation on the part of intermediary', 'Cancelled'].map((item) => ({ label: item, value: item }))
  const negative_resons = ['Treatment', 'Did not want an appointment', 'Multi-year contract', 'Other'].map((item) => ({ label: item, value: item }))
  return workflow_form.appointment_took_place === false ? appointment_did_not_took_place_resons : workflow_form.appointment_took_place === true && workflow_form.outcome == 'Negative' ? negative_resons : []
})

const hasPermissionToContinueWorkflow = computed(() => {
  return ['process_lead_again', 'quality_check', 'arrange_appointment_call', 'status_not_confirmed', 'appointment_reminder_call', 'process_quality_topic', 'perform_quality_check', 'intermediary_feedback_capture'].some(
    (permission) => hasPermission(permission)
  )
})

const rules = {
  correspondence_language: {
    required: helpers.withMessage('', required),
  },
  salutation: {
    required: helpers.withMessage('', required),
  },
  first_name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
  },
  last_name: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
  },
  date_of_birth: {
    required: helpers.withMessage('', required),
  },
  phone_number: {
    required: helpers.withMessage('', required),
  },
  phone_number_iso_code: {
    required: helpers.withMessage('', required),
  },
  email: {
    required: helpers.withMessage('', required),
    email: helpers.withMessage('Invalid Email format', email),
  },
  street: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
  },
  house_number: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
  },
  zip_code: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    validFormat: helpers.withMessage('', helpers.regex(/^[\d\p{L}\p{M}\p{Zs}]+$/u)),
  },
  city: {
    required: helpers.withMessage('', required),
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
  },
  country_iso_code: {
    required: helpers.withMessage('', required),
  },
  canton: {
    required: helpers.withMessage('', required),
  },
  region: {
    required: helpers.withMessage('', required),
  },
  contact_desired: {
    required: helpers.withMessage('', required),
  },
  remarks_control_lead: {
    maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 250 }), maxLength(250)),
  },
}

const workflow_rules = {
  data_verified_updated: {
    required: requiredIf(() => hasPermission('process_lead_again')),
  },

  lead: {
    required: requiredIf(() => hasPermission('quality_check')),
  },

  remarks_control_lead: {
    maxLength: maxLength(250),
  },

  lead_control_task: {
    required: requiredIf(() => hasPermission('quality_check') && (workflow_form.lead == 'Not confirmed' || workflow_form.lead == 'Back to Call Agent')),
    maxLength: maxLength(50),
  },

  lead_control_task_remarks: {
    required: requiredIf(() => hasPermission('status_not_confirmed')),
    maxLength: maxLength(250),
  },

  lead_control_task_status: {
    required: requiredIf(() => hasPermission('status_not_confirmed')),
  },

  feedback: {
    required: requiredIf(() => hasPermission('arrange_appointment_call') || hasPermission('process_quality_topic')),
  },

  feedback_remarks: {
    maxLength: maxLength(250),
  },

  call_date: {
    required: requiredIf(() => hasPermission('arrange_appointment_call') && workflow_form.feedback == 'Call later'),
    date_is_future: (value) => {
      return workflow_form.call_date ? dayjs(value).isAfter(dayjs()) : true
    },
  },

  call_time: {
    required: requiredIf(() => hasPermission('arrange_appointment_call') && workflow_form.feedback == 'Call later'),
    valid_format: helpers.regex(/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/),
  },

  control_status_appointment: {
    required: requiredIf(() => hasPermission('perform_quality_check')),
  },

  remarks_control_appointment: {
    maxLength: maxLength(250),
  },

  appointment_control_task: {
    required: requiredIf(() => hasPermission('perform_quality_check') && workflow_form.control_status_appointment == 'Back to call agent'),
    maxLength: maxLength(50),
  },

  appointment_control_task_remarks: {
    required: requiredIf(() => hasPermission('process_quality_topic')) && workflow_form.feedback == 'Appointment',
    maxLength: maxLength(250),
  },
  appointment_control_task_status: {
    required: requiredIf(() => hasPermission('process_quality_topic')) && workflow_form.feedback == 'Appointment',
  },

  appointment_reminder_status: {
    required: requiredIf(() => hasPermission('appointment_reminder_call')),
  },

  appointment_reminder_remarks: {
    maxLength: maxLength(250),
  },

  appointment_took_place: {
    required: requiredIf(() => hasPermission('intermediary_feedback_capture')),
  },
  reason: {
    required: requiredIf(
      () => (hasPermission('intermediary_feedback_capture') && workflow_form.appointment_took_place === false) || (workflow_form.appointment_took_place === true && workflow_form.outcome == 'Negative')
    ),
  },
  outcome: {
    required: requiredIf(() => hasPermission('intermediary_feedback_capture') && workflow_form.appointment_took_place === true),
  },
  contracts_concluded: {
    required: requiredIf(() => hasPermission('intermediary_feedback_capture') && workflow_form.outcome === 'Positive' && workflow_form.appointment_took_place === true),
    integer,
  },
  other: {
    maxLength: (value) => workflow_form.reason == 'Other' && workflow_form.outcome == 'Negative' ? value?.length < 251 : true,
  },
  follow_up_contact_date: {
    required: requiredIf(() => hasPermission('intermediary_feedback_capture') && workflow_form.outcome == 'Follow up contact necessary' && workflow_form.appointment_took_place === true),
    date_is_future: (value) => {
      return workflow_form.call_date ? dayjs(value).isAfter(dayjs()) : true
    },
  },

  follow_up_contact_time: {
    required: requiredIf(() => hasPermission('intermediary_feedback_capture') && workflow_form.outcome == 'Follow up contact necessary' && workflow_form.appointment_took_place === true),
    valid_format: helpers.regex(/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/),
  },
  expiry_year: {
    required: requiredIf(() => hasPermission('intermediary_feedback_capture') && workflow_form.appointment_took_place === true && workflow_form.outcome == 'Negative' && workflow_form.reason == 'Multi-year contract'),
    date_is_future: (value) => {
      return workflow_form.call_date ? dayjs(value).isAfter(dayjs()) : true
    },
  },

  intermediary_remarks: {
    maxLength: maxLength(250),
  },
}

const v$ = useVuelidate(rules, form)
const vWorkflow$ = useVuelidate(workflow_rules, workflow_form)

const getContactRecordData = async (id) => {
  try {
    const { data } = await axios.get(route('contact-data-records.edit', { contact_data_record: id }))
    Object.assign(contact_data_record, data)

    for (const key in data) {
      if (Object.hasOwnProperty.call(form, key)) {
        form[key] = data[key]
        form.originalData[key] = data[key]
      }
    }
  } catch (error) {
    console.log(error)
  }
}

const getOptionsData = async () => {
  try {
    const { data } = await axios.get(route('contact-data-records.get-create-options.data'))

    for (const key in data) {
      if (Object.hasOwnProperty.call(options, key)) {
        options[key] = data[key]
      }
    }
  } catch (error) {
    console.log(error)
  }
}

const submit = async () => {
  try {
    const res = await form.put(route('contact-data-records.update', { contact_data_record: contact_data_record.id }))
    router.push({ name: 'contact-data-records-leads-index' })
  } catch (error) {}
}

const continueWorkflow = async () => {
  if (vWorkflow$.value.$invalid) {
    vWorkflow$.value.$touch()
    $vfm.show('field-missing')
    return
  }
  try {
    const res = await workflow_form.post(route('continue-workflow', { contact_data_record: contact_data_record.id }))
    await getContactRecordData(vRoute.params.id)

    for (const key in workflow_form) {
      if (Object.hasOwnProperty.call(workflow_form.originalData, key)) {
        workflow_form[key] = workflow_form.originalData[key]
      }
    }
    vWorkflow$.value.$reset()
  } catch (error) {
    $vfm.show('field-missing')
    console.log(error)
  }
}

const notReached = async () => {
  try {
    await axios.post(route('continue-workflow', { contact_data_record: contact_data_record.id }), {
      contact_record_status: 'New-Not reached',
    })
    await getContactRecordData(vRoute.params.id)
    for (const key in workflow_form) {
      if (Object.hasOwnProperty.call(workflow_form.originalData, key)) {
        workflow_form[key] = workflow_form.originalData[key]
      }
    }
  } catch (error) {
    console.log(error)
  }
}

function hasPermission(permission) {
  switch (permission) {
    case 'process_lead_again':
      return ['lead_again', 'termination_lead'].includes(contact_data_record.category) && !contact_data_record.data_verified_updated

    case 'quality_check':
      return (
        (contact_data_record.contact_record_status == 'New' || contact_data_record.contact_record_status == 'Completed') &&
        (contact_data_record.category == 'lead' || (['lead_again', 'termination_lead'].includes(contact_data_record.category) && contact_data_record.data_verified_updated)) &&
        (userStore.hasRole(['company_admin', 'Leader', 'Manager']) || (userStore.hasRole('Quality controller') && userStore.user.alignment?.includes('1')))
      )

    case 'arrange_appointment_call':
      return (
        ['Confirmed', 'Rund', 'Call later', 'Not Reached', 'Not reached - Appointment reminder', 'Appointment did not take place'].includes(contact_data_record.contact_record_status) &&
        ['lead', 'lead_again', 'termination_lead', 'Appointment'].includes(contact_data_record.category) &&
        (userStore.hasRole(['company_admin', 'Leader', 'Manager']) || (userStore.hasRole('Call agent') && userStore.user.alignment?.includes('2')))
      )

    case 'perform_quality_check':
      return (
        contact_data_record.category == 'Appointment' &&
        ['terminated', 'Quality topic solved'].includes(contact_data_record.contact_record_status) &&
        (userStore.hasRole(['company_admin', 'Leader', 'Manager']) || (userStore.hasRole('Quality controller') && userStore.user.alignment?.includes('2')))
      )

    case 'process_quality_topic':
      return (
        contact_data_record.category == 'lead' &&
        contact_data_record.contact_record_status == 'Quality Topic' &&
        (userStore.hasRole(['company_admin', 'Leader', 'Manager']) || (userStore.hasRole('Call agent') && userStore.user.alignment?.includes('2')))
      )

    case 'appointment_reminder_call':
      return (
        contact_data_record.category == 'Appointment' &&
        contact_data_record.contact_record_status == 'Confirmed (Reminder pending)' &&
        (userStore.hasRole(['company_admin', 'Leader', 'Manager']) || (userStore.hasRole('Quality controller') && userStore.user.alignment?.includes('2')))
      )

    case 'status_not_confirmed':
      return contact_data_record.contact_record_status == 'Not confirmed' && (userStore.hasRole(['company_admin', 'Leader', 'Manager']) || (userStore.hasRole('Call agent') && userStore.user.alignment?.includes('1')))

    case 'intermediary_feedback_capture':
      return ['Allocated', 'Open'].includes(contact_data_record.contact_record_status) && userStore.hasRole(['company_admin', 'Leader', 'Manager', 'Admin']) && contact_data_record.category == 'Appointment'
    default:
      return false
  }
}

onMounted(async () => {
  await getOptionsData()
  await getContactRecordData(vRoute.params.id)
})
</script>
