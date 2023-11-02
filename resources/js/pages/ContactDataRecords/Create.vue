<template>
    <div class="bg-white shadow-xl shadow-#1f2937-800/25 px-[30px] py-[40px] overflow-y-auto mr-[10px] rounded-[15px]">
        <h1 class="text-formHeading text-title font-poppins mb-[60px]">
            {{ $t("Add New Contact Data Record") }}
        </h1>

        <form @submit.prevent="submit">
            <div class="section general__information">
                <Accordion title="General Contact Data Record Information">
                    <!-- <div class="form grid grid-cols-[460px,_460px,_400px]"> -->
                    <div class="form grid grid-cols-3 gap-x-[120px]">
                        <div class="flex flex-col">
                            <h2 class="label text-formLabel font-inter text-input mb-[10px]">
                                {{ $t("ID") }}*
                            </h2>
                            <p class="text-value text-4 font-semibold font-inter leading-[19px]">
                                {{ prefix_id }}
                            </p>
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Source" placeholder="Select Source" :asterisk="true" :options="source_lists"
                                v-model="form.source" optionsClass="w-full right-0" :disabled="true" class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Category" placeholder="Select Category" :asterisk="true"
                                :options="category_lists" v-model="form.category" optionsClass="w-full right-0" :disabled="true"
                                class="w-full" />
                        </div>
                        <div class="spacer mt-[45px] col-span-3"></div>
                        <div class="flex flex-col">
                            <SingleSelect label="Campaign" placeholder="Select Campaign" :asterisk="true"
                                :options="campaign_lists" v-model="form.campaign_id" optionsClass="w-full right-0" :disabled="true"
                                class="w-full" />
                        </div>
                    </div>
                </Accordion>
            </div>
            <div class="section personal__information mt-[34px]">
                <Accordion title="Person Information">
                    <div class="form grid grid-cols-3 gap-x-[120px]">
                        <div class="flex flex-col">
                            <SingleLanguageSelect v-model="form.correspondence_language" label="Correspondence Language"
                                placeholder="Select Correspondence language" labelClass="whitespace-nowrap" :asterisk="true"
                                class="w-full"
                                :error="v$.correspondence_language.$errors[0]?.$message"
                            />
                        </div>
                        <div class="flex flex-col">
                            <MultiLanguageSelector v-model="form.other_languages" label="Other Languages"
                                placeholder="Select other languages" labelClass="whitespace-nowrap" :asterisk="false"
                                class="w-full" :searchables="false" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Salutation" placeholder="Select salutation" :asterisk="true"
                                :options="salutation_lists" v-model="form.salutation" optionsClass="w-full right-0"
                                class="w-full"
                                :error="v$.salutation.$errors[0]?.$message"
                            />
                        </div>
                        <div class="spacer mt-[45px] col-span-3"></div>
                        <div class="flex flex-col">
                            <TextInput label="First Name" :placeholder="'Enter first name'" :asterisk="true" maxLength="31"
                                v-model="form.first_name" class="w-full"
                                :error="v$.first_name.$errors[0]?.$message"
                                @input="() => { v$.first_name.$touch()}"
                            />
                        </div>
                        <div class="flex flex-col">
                            <TextInput label="Last Name" :placeholder="'Enter last name'" :asterisk="true" maxLength="31"
                                v-model="form.last_name" class="w-full"
                                :error="v$.last_name.$errors[0]?.$message"
                                @input="() => { v$.last_name.$touch()}"
                            />
                        </div>
                        <div class="flex flex-col">
                            <SingleDatePicker v-model="form.date_of_birth" label="Date of Birth" :asterisk="true"  :error="v$.date_of_birth.$errors[0]?.$message" />
                        </div>
                        <div class="spacer mt-[45px] col-span-3"></div>
                        <div class="flex flex-col">
                            <PhoneNumberInput label="Mobile Phone Number" v-model:country_code="form.phone_number_iso_code"
                                v-model:phone_number="form.phone_number" class="w-full" :asterisk="true"
                                :error="v$.phone_number_iso_code.$errors.length || v$.phone_number.$errors.length ? true : false"
                            />
                        </div>
                        <div class="flex flex-col">
                            <TextInput label="Email" :placeholder="'Enter e-mail'" :asterisk="true" v-model="form.email"
                                class="w-full"
                                @input="() => { v$.email.$touch()}"
                                :error="v$.email.$errors[0]?.$message"
                            />
                        </div>
                    </div>
                </Accordion>
            </div>
            <div class="section address__information mt-[34px]">
                <Accordion title="Address">
                    <div class="form grid grid-cols-3 gap-x-[120px]">
                        <div class="flex flex-col">
                            <TextInput label="Street" placeholder="Enter street" :asterisk="true" maxLength="31"
                                v-model="form.street" class="w-full"
                                :error="v$.street.$errors[0]?.$message"
                                @input="() => { v$.street.$touch()}"
                            />
                        </div>
                        <div class="flex flex-col">
                            <TextInput label="House number" placeholder="Enter house number" :asterisk="true"
                                maxLength="31" v-model="form.house_number" class="w-full"
                                :error="v$.house_number.$errors[0]?.$message"
                                @input="() => { v$.house_number.$touch()}"
                            />
                        </div>
                        <div class="flex flex-col">
                            <TextInput label="Zip Code" placeholder="Enter zip code" :asterisk="true"
                                v-model="form.zip_code" class="w-full"
                                :error="v$.zip_code.$errors[0]?.$message"
                                @input="() => { v$.zip_code.$touch()}"
                            />
                        </div>
                        <div class="spacer mt-[45px] col-span-3"></div>
                        <div class="flex flex-col">
                            <TextInput label="City" placeholder="Enter city" :asterisk="true" maxLength="31"
                                v-model="form.city" class="w-full"
                                :error="v$.city.$errors[0]?.$message"
                                @input="() => { v$.city.$touch()}"
                            />
                        </div>
                        <div class="flex flex-col">
                            <CountrySelect label="Country" placeholder="Select Country" v-model="form.country_iso_code"
                                :asterisk="true" class="w-full"
                                :error="v$.country_iso_code.$errors[0]?.$message"
                            />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Canton" placeholder="Select canton" :asterisk="true" :options="canton_lists" :searchable="true" :searchables="['label']"
                                v-model="form.canton" optionsClass="w-full right-0" class="w-full"
                                :error="v$.canton.$errors[0]?.$message"
                            />

                        </div>
                        <div class="spacer mt-[45px] col-span-3"></div>
                        <div class="flex flex-col">
                            <SingleSelect label="Region" placeholder="Select region" :asterisk="true" :options="region_lists"
                               v-model="form.region" optionsClass="w-full right-0" class="w-full"
                               :error="v$.region.$errors[0]?.$message"
                            />
                        </div>
                    </div>
                </Accordion>
            </div>
            <div class="section termination__information mt-[34px]">
                <Accordion title="Appointment relevant Information">
                    <div class="form grid grid-cols-3 gap-x-[120px]">
                        <div class="flex flex-col">
                            <SingleSelect label="Car Insurance" placeholder="Select insurance" :asterisk="false"
                                :options="car_insurance_lists" v-model="form.car_insurance" optionsClass="w-full right-0"
                                class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="3rd Pillar" placeholder="Select 3rd pillar" :asterisk="false"
                                :options="third_piller_lists" v-model="form.third_piller" optionsClass="w-full right-0"
                                class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Household Goods" placeholder="Select household goods" :asterisk="false"
                                :options="household_good_lists" v-model="form.household_goods" optionsClass="w-full right-0"
                                class="w-full" />
                        </div>
                        <div class="spacer mt-[45px] col-span-3"></div>
                        <div class="flex flex-col">
                            <SingleSelect label="Legal Protection" placeholder="Select legal protection" :asterisk="false"
                                :options="legal_protection_lists" v-model="form.legal_protection"
                                optionsClass="w-full right-0" class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Health Status" placeholder="Select health condition" :asterisk="false"
                                :options="health_status_lists" v-model="form.health_status" optionsClass="w-full right-0"
                                class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Health Insurance" placeholder="Select health insurance" :asterisk="false"
                                :options="health_insurance_lists" v-model="form.health_insurance"
                                optionsClass="w-full right-0" class="w-full" />
                        </div>
                        <div class="spacer mt-[45px] col-span-3 "></div>
                        <div class="flex flex-col">
                            <SingleSelect label="Contact person for insurance questions" placeholder="Select insurance questions" :asterisk="false"
                                :options="contact_person_for_insurance_question_lists"
                                v-model="form.contact_person_for_insurance_questions" optionsClass="w-full right-0"
                                labelClass="whitespace-nowrap" class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="contact_data_save" placeholder="Select savings" :asterisk="false"
                                :options="save_lists" v-model="form.save" optionsClass="w-full right-0" class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Last Health Insurance Change" placeholder="Last Health Insurance Change"
                                :asterisk="false" :options="last_health_insurance_change_lists"
                                v-model="form.last_health_insurance_change" optionsClass="w-full right-0"
                                class="w-full" />
                        </div>
                        <div class="spacer mt-[45px] col-span-3"></div>
                        <div class="flex flex-col">
                            <SingleSelect label="Satisfaction" placeholder="Select satisfaction" :asterisk="false"
                                :options="satisfaction_lists" v-model="form.satisfaction" optionsClass="w-full right-0"
                                class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Number of persons in household" placeholder="Select number of persons in household" :asterisk="false"
                                :options="number_of_persons_in_household_lists"
                                v-model="form.number_of_persons_in_household" optionsClass="w-full right-0"
                                class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Work Activity" placeholder="Select work activity" :asterisk="false"
                                :options="work_activity_lists" v-model="form.work_activity" optionsClass="w-full right-0"
                                class="w-full" />
                        </div>
                    </div>
                </Accordion>
            </div>
            <div class="section contact__information mt-[34px]">
                <Accordion title="Contact information">
                    <div v-if="formOptions.no_information" class="form grid "
                        :class="[formOptions.no_information ? 'grid-cols-3 gap-x-[120px]' : 'grid-cols-3 gap-x-[120px]']">
                        <div class="flex flex-col">
                            <CheckboxInput label="Availability" labelClass="mb-4" checkLabel="No information"
                                v-model="formOptions.no_information" class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Desired consulting channel" placeholder="Select consulting channel" :asterisk="false"
                                :options="desired_consultation_channel_lists" v-model="form.desired_consultation_channel"
                                optionsClass="w-full right-0" class="w-full" />
                        </div>
                        <div class="flex flex-col">
                            <SingleSelect label="Desired contact" placeholder="Select contact desired" :asterisk="true"
                                :options="contact_desired_lists" v-model="form.contact_desired"
                                optionsClass="w-full right-0" class="w-full"
                                :error="v$.contact_desired.$errors[0]?.$message"
                            />
                        </div>
                    </div>
                    <div v-else >
                        <div>
                            <div class="flex flex-col">
                                <CheckboxInput label="Availability" labelClass="mb-4" checkLabel="No information"
                                    v-model="formOptions.no_information" class="w-full" />
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-col">
                            </div>
                            <div class="spacer mt-[35px] col-span-2"></div>
                            <div class="work__availability flex  mb-[44px] w-[795px]" style=" grid-column: 1 / 1;">
                                <div class="work__availability__days w-[217px]">
                                    <div class="work__availability__days--day">
                                        <div class="work__availability--row items-start">
                                            <CheckboxInput
                                                :checkLabel="isAllSelected ? 'availability_deselect_all' : 'availability_select_all'"
                                                v-model="isAllSelected" class="w-full" />
                                        </div>
                                        <div class="work__availability--row">
                                            <CheckboxInput checkLabel="Monday"
                                                v-model="workAvailabilityDays.work_availability_monday" class="w-full" />
                                        </div>
                                        <div class="work__availability--row">
                                            <CheckboxInput checkLabel="Tuesday"
                                                v-model="workAvailabilityDays.work_availability_tuesday" class="w-full" />
                                        </div>
                                        <div class="work__availability--row">
                                            <CheckboxInput checkLabel="Wednesday"
                                                v-model="workAvailabilityDays.work_availability_wednesday" class="w-full" />
                                        </div>
                                        <div class="work__availability--row">
                                            <CheckboxInput checkLabel="Thursday"
                                                v-model="workAvailabilityDays.work_availability_thursday" class="w-full" />
                                        </div>
                                        <div class="work__availability--row">
                                            <CheckboxInput checkLabel="Friday"
                                                v-model="workAvailabilityDays.work_availability_friday" class="w-full" />
                                        </div>
                                        <div class="work__availability--row">
                                            <CheckboxInput checkLabel="Saturday"
                                                v-model="workAvailabilityDays.work_availability_saturday" class="w-full" />
                                        </div>
                                        <div class="work__availability--row">
                                            <CheckboxInput checkLabel="Sunday"
                                                v-model="workAvailabilityDays.work_availability_sunday" class="w-full" />
                                        </div>
                                    </div>
                                </div>
                                <div class="work__availability__times flex w-[580px] gap-5">
                                    <div
                                        class="work__availability__days--day__time work__availability__days--first_start_time w-[125px]">
                                        <div class="work__availability--row__header">Von</div>
                                        <div class="work__availability--row">
                                            <input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_monday.first_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_monday"
                                                :class="[vtime$.work_availability_monday.first_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_monday.first_start_time.$touch()"
                                            />
                                        </div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_tuesday.first_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_tuesday"
                                                :class="[vtime$.work_availability_tuesday.first_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_tuesday.first_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_wednesday.first_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_wednesday"
                                                :class="[vtime$.work_availability_wednesday.first_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_wednesday.first_start_time.$touch()"
                                        /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_thursday.first_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_thursday"
                                                :class="[vtime$.work_availability_thursday.first_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_thursday.first_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_friday.first_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_friday"
                                                :class="[vtime$.work_availability_friday.first_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_friday.first_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_saturday.first_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_saturday"
                                                :class="[vtime$.work_availability_saturday.first_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_saturday.first_start_time.$touch()"
                                        /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_sunday.first_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_sunday"
                                                :class="[vtime$.work_availability_sunday.first_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_sunday.first_start_time.$touch()"
                                        /></div>
                                    </div>
                                    <div
                                        class="work__availability__days--day__time work__availability__days--first_end_time w-[125px]">
                                        <div class="work__availability--row__header">Bis</div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_monday.first_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_monday"
                                                :class="[vtime$.work_availability_monday.first_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_monday.first_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_tuesday.first_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_tuesday"
                                                :class="[vtime$.work_availability_tuesday.first_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_tuesday.first_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_wednesday.first_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_wednesday"
                                                :class="[vtime$.work_availability_wednesday.first_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_wednesday.first_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_thursday.first_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_thursday"
                                                :class="[vtime$.work_availability_thursday.first_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_thursday.first_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_friday.first_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_friday"
                                                :class="[vtime$.work_availability_friday.first_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_friday.first_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_saturday.first_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_saturday"
                                                :class="[vtime$.work_availability_saturday.first_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_saturday.first_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_sunday.first_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_sunday"
                                                :class="[vtime$.work_availability_sunday.first_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_sunday.first_end_time.$touch()"
                                            /></div>
                                    </div>
                                    <div
                                        class="work__availability__days--day__time work__availability__days--last_start_time w-[125px]">
                                        <div class="work__availability--row__header">Von</div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_monday.last_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_monday"
                                                :class="[vtime$.work_availability_monday.last_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_monday.last_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_tuesday.last_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_tuesday"
                                                :class="[vtime$.work_availability_tuesday.last_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_tuesday.last_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_wednesday.last_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_wednesday"
                                                :class="[vtime$.work_availability_wednesday.last_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_wednesday.last_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_thursday.last_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_thursday"
                                                :class="[vtime$.work_availability_thursday.last_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_thursday.last_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_friday.last_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_friday"
                                                :class="[vtime$.work_availability_friday.last_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_friday.last_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_saturday.last_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_saturday"
                                                :class="[vtime$.work_availability_saturday.last_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_saturday.last_start_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_sunday.last_start_time"
                                                :readonly="!workAvailabilityDays.work_availability_sunday"
                                                :class="[vtime$.work_availability_sunday.last_start_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_sunday.last_start_time.$touch()"
                                            /></div>
                                    </div>
                                    <div
                                        class="work__availability__days--day__time work__availability__days--last_end_time w-[125px]">
                                        <div class="work__availability--row__header">Bis</div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_monday.last_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_monday"
                                                :class="[vtime$.work_availability_monday.last_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_monday.last_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_tuesday.last_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_tuesday"
                                                :class="[vtime$.work_availability_tuesday.last_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_tuesday.last_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_wednesday.last_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_wednesday"
                                                :class="[vtime$.work_availability_wednesday.last_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_wednesday.last_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_thursday.last_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_thursday"
                                                :class="[vtime$.work_availability_thursday.last_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_thursday.last_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_friday.last_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_friday"
                                                :class="[vtime$.work_availability_friday.last_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_friday.last_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_saturday.last_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_saturday"
                                                :class="[vtime$.work_availability_saturday.last_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_saturday.last_end_time.$touch()"
                                            /></div>
                                        <div class="work__availability--row"><input class="work__availability__time--input"
                                                v-model="workAvailabilityForm.work_availability_sunday.last_end_time"
                                                :readonly="!workAvailabilityDays.work_availability_sunday"
                                                :class="[vtime$.work_availability_sunday.last_end_time.$invalid ? 'border-[1px] border-error' : '']"
                                                @input="vtime$.work_availability_sunday.last_end_time.$touch()"
                                            /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="spacer col-span-2"></div>
                        </div>

                        <div :class="['grid grid-cols-3 gap-x-[120px]']">
                            <div class="flex flex-col">
                                <SingleSelect label="Desired consulting channel" placeholder="Select consulting channel" :asterisk="false"
                                    :options="desired_consultation_channel_lists" v-model="form.desired_consultation_channel"
                                    optionsClass="w-full right-0" class="w-full" />
                            </div>
                            <div class="flex flex-col">
                                <SingleSelect label="Desired contact" placeholder="Select contact desired" :asterisk="true"
                                    :options="contact_desired_lists" v-model="form.contact_desired"
                                    optionsClass="w-full right-0" class="w-full"
                                    :error="v$.contact_desired.$errors[0]?.$message"
                                />
                            </div>

                        </div>
                    </div>
                </Accordion>

            </div>
            <div class="section remarks mt-[44px] grid grid-cols-3 gap-x-[120px]">
                <TextareaInput label="Notes" placeholder="Enter Notes" :asterisk="false" maxLength="251"
                    v-model="form.remarks_control_lead" class="w-full"
                    :error="v$.remarks_control_lead.$errors[0]?.$message"
                    @input="() => { v$.remarks_control_lead.$touch()}"
                />
            </div>


            <div class="spacer mt-[100px] col-span-3"></div>
            <div class="flex col-span-2 gap-[18px]">
                <ButtonGradient class="w-[20%]" type="submit" :disabled="form.busy">
                    {{ $t("Save") }}
                </ButtonGradient>

                <ButtonWhite class="w-[20%]" type="button" @click="
                    $vfm.show('redirect-modal', {
                        title: 'Cancel contact record entry?',
                        description: `When you abort, all data is discarded. Are you sure you really want to cancel this contact record entry?`,
                    })
                ">
                    {{ $t("Cancel") }}
                </ButtonWhite>
            </div>
        </form>
    </div>
</template>

<script setup>
import ButtonGradient from "@/components/button/Gradient.vue";
import ButtonWhite from "@/components/button/White.vue";
import TextInput from "@/components/form/TextInput.vue";
import Form from "vform";
import RadioInput from "@/components/form/RadioInputs.vue";
import { reactive, computed, ref } from "@vue/reactivity";
import { inject, onBeforeMount, watch } from "@vue/runtime-core";
import MultiSelect from "@/components/form/MultiSelect.vue";
import SingleSelect from '@/components/form/SingleSelect.vue'
import PlusIcon from "@/components/icons/Plus.vue";
import { trans } from "laravel-vue-i18n";
import CountrySelect from "@/components/form/CountrySelect.vue";
import { useVuelidate } from "@vuelidate/core";
import { required, email, maxLength, helpers, requiredIf } from "@vuelidate/validators";
import PhoneNumberInput from "./components/PhoneNumberInput.vue";
import axios from "axios";
import { notificationShowStore } from "@/store/notification.js";
import { useRouter } from "vue-router";

import Accordion from "@/components/utils/Accordion.vue";
import CheckboxInput from "../../components/form/CheckboxInput.vue";
import TextareaInput from "../../components/form/TextareaInput.vue";
import SingleDatePicker from "../../components/form/SingleDatePicker.vue";
import MultiLanguageSelector from "../../components/form/MultiLanguageSelector.vue";
// import AllLanguagesSelect from "../../components/form/AllLanguagesSelect.vue";
import SingleLanguageSelect from "../../components/form/SingleLanguageSelect.vue";

const notification = notificationShowStore();
const router = useRouter();
const $vfm = inject("$vfm");

const prefix_id = ref("");
const source_lists = ref([]);
const category_lists = ref([]);
const campaign_lists = ref([]);
const salutation_lists = ref([]);
const canton_lists = ref([]);
const region_lists = ref([]);

const car_insurance_lists = ref([]);
const third_piller_lists = ref([]);
const household_good_lists = ref([]);
const legal_protection_lists = ref([]);
const health_status_lists = ref([]);
const health_insurance_lists = ref([]);
const contact_person_for_insurance_question_lists = ref([]);
const save_lists = ref([]);
const last_health_insurance_change_lists = ref([]);
const satisfaction_lists = ref([]);
const number_of_persons_in_household_lists = ref([]);
const work_activity_lists = ref([]);
const desired_consultation_channel_lists = ref([]);
const contact_desired_lists = ref([]);


const form = reactive(
    new Form({
        source: 'not_online',
        category: 'lead',
        campaign_id: 1,
        correspondence_language: null,
        other_languages: [],
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
);

const workAvailabilityForm = reactive({
    work_availability_monday: {
        first_start_time: '',
        first_end_time: '',
        last_start_time: '',
        last_end_time: ''
    },
    work_availability_tuesday: {
        first_start_time: '',
        first_end_time: '',
        last_start_time: '',
        last_end_time: ''
    },
    work_availability_wednesday: {
        first_start_time: '',
        first_end_time: '',
        last_start_time: '',
        last_end_time: ''
    },
    work_availability_thursday: {
        first_start_time: '',
        first_end_time: '',
        last_start_time: '',
        last_end_time: ''
    },
    work_availability_friday: {
        first_start_time: '',
        first_end_time: '',
        last_start_time: '',
        last_end_time: ''
    },
    work_availability_saturday: {
        first_start_time: '',
        first_end_time: '',
        last_start_time: '',
        last_end_time: ''
    },
    work_availability_sunday: {
        first_start_time: '',
        first_end_time: '',
        last_start_time: '',
        last_end_time: ''
    },
});

const formOptions = ref({
    no_information: false,
    // work_availability_select_all: false,
    // work_availability_monday: false,
    // work_availability_tuesday: false,
    // work_availability_wednesday: false,
    // work_availability_thursday: false,
    // work_availability_friday: false,
    // work_availability_saturday: false,
    // work_availability_sunday: false,
});

const workAvailabilityDays = reactive({
    work_availability_monday: false,
    work_availability_tuesday: false,
    work_availability_wednesday: false,
    work_availability_thursday: false,
    work_availability_friday: false,
    work_availability_saturday: false,
    work_availability_sunday: false,
})

const isAllSelected = computed({
    get: () => Object.values(workAvailabilityDays).filter(i => i == true).length == 7,
    set: (value) => {
        if (isAllSelected.value) {
            Object.keys(workAvailabilityDays).forEach(key => {
                workAvailabilityDays[key] = false;
            })
        } else {
            Object.keys(workAvailabilityDays).forEach(key => {
                workAvailabilityDays[key] = true;
            })
        }
    }
})


// watch(
//     ()=> formOptions.work_availability_select_all,
//     (newValue)=> {
//         if(newValue){
//             workAvailabilityDays.work_availability_monday= true;
//             workAvailabilityDays.work_availability_tuesday= true;
//             workAvailabilityDays.work_availability_wednesday= true;
//             workAvailabilityDays.work_availability_thursday= true;
//             workAvailabilityDays.work_availability_friday= true;
//             workAvailabilityDays.work_availability_saturday= true;
//             workAvailabilityDays.work_availability_sunday= true;
//         }else  {
//             workAvailabilityDays.work_availability_monday= false;
//             workAvailabilityDays.work_availability_tuesday= false;
//             workAvailabilityDays.work_availability_wednesday= false;
//             workAvailabilityDays.work_availability_thursday= false;
//             workAvailabilityDays.work_availability_friday= false;
//             workAvailabilityDays.work_availability_saturday= false;
//             workAvailabilityDays.work_availability_sunday= false;
//         }
//     }
// )


// const country_iso_code = computed({
//   get: () => form.country_iso_code,
//   set: (value) => {
//     form.country_iso_code = value;
//     if (!form.phone_number_iso_code || !form.phone_number)
//       form.phone_number_iso_code = value;
//   },
// });



const rules = {
    // first_name: {
    //     // required: helpers.withMessage('', required),
    //     maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
    // },
    // source: {
    //     required: helpers.withMessage('', required),
    // },
    // category: {
    //     required: helpers.withMessage('', required),
    // },
    // campaign_id: {
    //     required: helpers.withMessage('', required),
    // },
    correspondence_language: {
        required: helpers.withMessage('', required),
    },
    salutation: {
        required: helpers.withMessage('', required),
    },
    first_name: {
        required: helpers.withMessage('', required),
        maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        validFormat: helpers.withMessage('', helpers.regex(/^[-\p{L}\p{M}\p{Zs}]+$/u)),
    },
    last_name: {
        required: helpers.withMessage('', required),
        maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        validFormat: helpers.withMessage('', helpers.regex(/^[-\p{L}\p{M}\p{Zs}]+$/u)),
    },
    date_of_birth: {
        required: helpers.withMessage('', required),
        // maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        // validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
    },
    phone_number: {
        required: helpers.withMessage('', required),
        // email: helpers.withMessage('Invalid Email format', email),
    },
    phone_number_iso_code: {
        required: helpers.withMessage('', required),
        // email: helpers.withMessage('Invalid Email format', email),
    },
    email: {
        required: helpers.withMessage('', required),
        email: helpers.withMessage('Invalid Email format', email),
    },
    street: {
        required: helpers.withMessage('', required),
        maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        // validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
    },
    house_number: {
        required: helpers.withMessage('', required),
        maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        // validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
    },
    zip_code: {
        required: helpers.withMessage('', required),
        maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        validFormat: helpers.withMessage('', helpers.regex(/^[\d\p{L}\p{M}\p{Zs}]+$/u)),
    },
    city: {
        required: helpers.withMessage('', required),
        maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        // validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
    },
    country_iso_code: {
        required: helpers.withMessage('', required),
        // maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        // validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
    },
    canton: {
        required: helpers.withMessage('', required),
        // maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        // validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
    },
    region: {
        required: helpers.withMessage('', required),
        // maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        // validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
    },
    contact_desired: {
        required: helpers.withMessage('', required),
        // maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 30 }), maxLength(30)),
        // validFormat: helpers.withMessage('', helpers.regex(/^[\p{L}\p{M}\p{Zs}]+$/u)),
    },
    remarks_control_lead: {
        // required: helpers.withMessage('', required),
        maxLength: helpers.withMessage(trans('Maximum :length characters possible', { length: 250 }), maxLength(250)),
    },
};

const v$ = useVuelidate(rules, form);

const time_format = helpers.regex(/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/);

const timeRules = {
    work_availability_monday: {
        first_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        first_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
    },
    work_availability_tuesday: {
        first_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        first_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
    },
    work_availability_wednesday: {
        first_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        first_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
    },
    work_availability_thursday: {
        first_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        first_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
    },
    work_availability_friday: {
        first_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        first_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
    },
    work_availability_saturday: {
        first_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        first_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
    },
    work_availability_sunday: {
        first_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        first_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_start_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
        last_end_time: {
            invalid_time: helpers.withMessage('invaild time format', time_format)
        },
    },
}

const vtime$ = useVuelidate(timeRules, workAvailabilityForm);

const submit = async () => {
    v$.value.$touch();
    vtime$.value.$touch();
    if (v$.value.$invalid || vtime$.value.$invalid) {
        $vfm.show("field-missing");
        return;
    }
    try {
        if(!formOptions.value.no_information) {
            form.workAvailabilityDays = workAvailabilityDays
            form.workAvailabilityForm = workAvailabilityForm
        }

        const res = await form.post(route("contact-data-records.store"));
        // console.log(res)
        notification.success(
            trans("The contact data record was successfully created", {
                // name: res.data.name,
            })
        );
        router.push({ name: "contact-data-records-leads-index" });
    } catch (error) {
        console.log(error, ["error"]);
    }
};


onBeforeMount(() => {
    getOptionsData();
});

const getOptionsData = async () => {
    try {
        const {data} = await axios.get(route("contact-data-records.get-create-options.data"));

        prefix_id.value = data.prefix_id
        source_lists.value = data.source_lists
        category_lists.value = data.category_lists
        campaign_lists.value = data.campaign_lists
        salutation_lists.value = data.salutation_lists
        canton_lists.value = data.canton_lists
        region_lists.value = data.region_lists
        car_insurance_lists.value = data.car_insurance_lists
        third_piller_lists.value = data.third_piller_lists
        household_good_lists.value = data.household_good_lists
        legal_protection_lists.value = data.legal_protection_lists
        health_status_lists.value = data.health_status_lists
        health_insurance_lists.value = data.health_insurance_lists
        contact_person_for_insurance_question_lists.value = data.contact_person_for_insurance_question_lists
        save_lists.value = data.save_lists
        last_health_insurance_change_lists.value = data.last_health_insurance_change_lists
        satisfaction_lists.value = data.satisfaction_lists
        number_of_persons_in_household_lists.value = data.number_of_persons_in_household_lists
        work_activity_lists.value = data.work_activity_lists
        desired_consultation_channel_lists.value = data.desired_consultation_channel_lists
        contact_desired_lists.value = data.contact_desired_lists

        // console.log(res.data);
    } catch (error) {
        console.log(error);
    }
};
</script>

<style scoped>
    .border-error {
        border: 1px solid red;
    }
</style>
