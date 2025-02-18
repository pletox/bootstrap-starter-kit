@extends('layouts.app')

@section('content')

    <div class="px-3">
        <div class="tw-overflow-hidden tw-bg-white tw-shadow sm:tw-rounded-lg">
            <div class="tw-px-4 tw-py-6 sm:tw-px-6">
                <h3 class="tw-text-base tw-font-semibold tw-leading-7 tw-text-gray-900">Applicant Information</h3>
                <p class="tw-mt-1 tw-max-w-2xl tw-text-sm tw-leading-6 tw-text-gray-500">Personal details and application.</p>
            </div>
            <div class="tw-border-t tw-border-gray-100">
                <dl class="tw-divide-y tw-divide-gray-100">
                    <div class="tw-px-4 tw-py-6 sm:tw-grid sm:tw-grid-cols-3 sm:tw-gap-4 sm:tw-px-6">
                        <dt class="tw-text-sm tw-font-medium tw-text-gray-900">Sale name</dt>
                        <dd class="tw-mt-1 tw-text-sm tw-leading-6 tw-text-gray-700 sm:tw-col-span-2 sm:tw-mt-0">{{ $sale->name }}</dd>
                    </div>
                    <div class="tw-px-4 tw-py-6 sm:tw-grid sm:tw-grid-cols-3 sm:tw-gap-4 sm:tw-px-6">
                        <dt class="tw-text-sm tw-font-medium tw-text-gray-900">Application for</dt>
                        <dd class="tw-mt-1 tw-text-sm tw-leading-6 tw-text-gray-700 sm:tw-col-span-2 sm:tw-mt-0">Backend Developer</dd>
                    </div>
                    <div class="tw-px-4 tw-py-6 sm:tw-grid sm:tw-grid-cols-3 sm:tw-gap-4 sm:tw-px-6">
                        <dt class="tw-text-sm tw-font-medium tw-text-gray-900">Email address</dt>
                        <dd class="tw-mt-1 tw-text-sm tw-leading-6 tw-text-gray-700 sm:tw-col-span-2 sm:tw-mt-0">margotfoster@example.com</dd>
                    </div>
                    <div class="tw-px-4 tw-py-6 sm:tw-grid sm:tw-grid-cols-3 sm:tw-gap-4 sm:tw-px-6">
                        <dt class="tw-text-sm tw-font-medium tw-text-gray-900">Salary expectation</dt>
                        <dd class="tw-mt-1 tw-text-sm tw-leading-6 tw-text-gray-700 sm:tw-col-span-2 sm:tw-mt-0">$120,000</dd>
                    </div>
                    <div class="tw-px-4 tw-py-6 sm:tw-grid sm:tw-grid-cols-3 sm:tw-gap-4 sm:tw-px-6">
                        <dt class="tw-text-sm tw-font-medium tw-text-gray-900">About</dt>
                        <dd class="tw-mt-1 tw-text-sm tw-leading-6 tw-text-gray-700 sm:tw-col-span-2 sm:tw-mt-0">Fugiat ipsum ipsum deserunt culpa aute sint do nostrud anim incididunt cillum culpa consequat. Excepteur qui ipsum aliquip consequat sint. Sit id mollit nulla mollit nostrud in ea officia proident. Irure nostrud pariatur mollit ad adipisicing reprehenderit deserunt qui eu.</dd>
                    </div>
                    <div class="tw-px-4 tw-py-6 sm:tw-grid sm:tw-grid-cols-3 sm:tw-gap-4 sm:tw-px-6">
                        <dt class="tw-text-sm tw-font-medium tw-leading-6 tw-text-gray-900">Attachments</dt>
                        <dd class="tw-mt-2 tw-text-sm tw-text-gray-900 sm:tw-col-span-2 sm:tw-mt-0">
                            <ul role="list" class="tw-divide-y tw-divide-gray-100 tw-rounded-md tw-border tw-border-gray-200">
                                <li class="tw-flex tw-items-center tw-justify-between tw-py-4 tw-pl-4 tw-pr-5 tw-text-sm tw-leading-6">
                                    <div class="tw-flex tw-w-0 tw-flex-1 tw-items-center">
                                        <svg class="tw-h-5 tw-w-5 tw-flex-shrink-0 tw-text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="tw-ml-4 tw-flex tw-min-w-0 tw-flex-1 tw-gap-2">
                                            <span class="tw-truncate tw-font-medium">resume_back_end_developer.pdf</span>
                                            <span class="tw-flex-shrink-0 tw-text-gray-400">2.4mb</span>
                                        </div>
                                    </div>
                                    <div class="tw-ml-4 tw-flex-shrink-0">
                                        <a href="#" class="tw-font-medium tw-text-indigo-600 hover:tw-text-indigo-500">Download</a>
                                    </div>
                                </li>
                                <li class="tw-flex tw-items-center tw-justify-between tw-py-4 tw-pl-4 tw-pr-5 tw-text-sm tw-leading-6">
                                    <div class="tw-flex tw-w-0 tw-flex-1 tw-items-center">
                                        <svg class="tw-h-5 tw-w-5 tw-flex-shrink-0 tw-text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="tw-ml-4 tw-flex tw-min-w-0 tw-flex-1 tw-gap-2">
                                            <span class="tw-truncate tw-font-medium">coverletter_back_end_developer.pdf</span>
                                            <span class="tw-flex-shrink-0 tw-text-gray-400">4.5mb</span>
                                        </div>
                                    </div>
                                    <div class="tw-ml-4 tw-flex-shrink-0">
                                        <a href="#" class="tw-font-medium tw-text-indigo-600 hover:tw-text-indigo-500">Download</a>
                                    </div>
                                </li>
                            </ul>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>

@endsection
