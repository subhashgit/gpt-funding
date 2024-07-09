import {Controller} from '@hotwired/stimulus';
import {driver} from "driver.js";
import "driver.js/dist/driver.css";
/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
export default class extends Controller {

    connect() {

        if (localStorage.getItem('driverjs-completed')) {
            return;
        }

        const company = [
            {
                element: 'body > div.flex.flex-col.h-screen.justify-between > div.w-full.pt-10.px-4.sm\\:px-6.md\\:px-8.lg\\:pl-72 > form > div:nth-child(1)',
                popover: {
                    title: 'Your Company Description',
                    description: 'Tell us about your organisation below so we can start to match you with relevant funding opportunities.',
                }
            },
            {
                element: 'body > div.flex.flex-col.h-screen.justify-between > div.w-full.pt-10.px-4.sm\\:px-6.md\\:px-8.lg\\:pl-72 > form > div:nth-child(2)',
                popover: {
                    title: 'Tell us the regions you work in',
                }
            },
            {
                element: 'body > div.flex.flex-col.h-screen.justify-between > div.w-full.pt-10.px-4.sm\\:px-6.md\\:px-8.lg\\:pl-72 > form > div:nth-child(3)',
                popover: {
                    title: 'Choose which best describes your organisation',
                }
            },

        ];

        const alerts = [
            {
                element: 'body > div.flex.flex-col.h-screen.justify-between > div.w-full.pt-10.px-4.sm\\:px-6.md\\:px-8.lg\\:pl-72 > div > section',
                popover:{
                    title: 'Alerts',
                    description: 'Set up alerts to receive notifications about new opportunities',
                },
            },
            {
                element: '#user_profile_notificationEmail',
                popover: {
                    title: 'Alerts',
                    description: 'Enter your email address to receive notifications. You can enter multiple emails separated by a comma',
                },
            },
            {
                element: 'body > div.flex.flex-col.h-screen.justify-between > div.w-full.pt-10.px-4.sm\\:px-6.md\\:px-8.lg\\:pl-72 > div > section > div.hs-accordion-group',
                popover: {
                    title: 'Alerts',
                    description: 'Select the filters you want to receive notifications about',
                },
            },
        ];

        const bidWriter = [
            {
                element: '#questionnaire_questions_0_answer',
                popover: {
                    title: 'Bid Writer',
                    description: 'Tell us about your project in as much detail as possible ',
                }
            },
            {
                element: '#bid-form > div > div:nth-child(4) > div.sm\\:col-span-9',
                popover: {
                    title: 'Bid Writer',
                    description: 'Select a postcode where the project will take place (optional)',
                }
            },
            {
                element: '#questionnaire_questions_1_answer',
                popover: {
                    title: 'Bid Writer',
                    description: 'Input a question from the funding application',
                }
            },
            {
                element: '#questionnaire_questions_1_instruction',
                popover: {
                    title: 'Bid Writer',
                    description: 'Include a word count (optional)',
                }
            },
        ];

        const driverObj = driver({
            showProgress: true,
            smoothScroll: true,
            allowClose: false,
            animate: true,
            steps: [
                {
                    popover: {
                        title: 'Welcome to FundinAI',
                        description: 'Follow the instructions to set up your account',
                    },
                },
                {
                    element: '#application-sidebar > nav > ul > li:nth-child(1)',
                    popover: {
                        title: 'Description',
                        description: 'Here is where you tell our Ai about your organisation ',
                        nextBtnText: 'Company',
                        onNextClick: (element, step, opts) => {
                            localStorage.setItem('driverjs-current-step', String(opts.state.activeIndex + 1));
                            document.location.href = '/company';
                        },
                    },
                },
                ...company,
                {
                    element: '#application-sidebar > nav > ul > li:nth-child(4)',
                    popover: {
                        title: 'Grants',
                        description: 'Here is our grant database',
                        nextBtnText: 'Grants',
                        onNextClick: (element, step, opts) => {
                            localStorage.setItem('driverjs-current-step', String(opts.state.activeIndex + 1));
                            document.location.href = '/grant';
                        },
                    },
                },
                {
                    element: 'body > div.flex.flex-col.h-screen.justify-between > div.w-full.pt-10.px-4.sm\\:px-6.md\\:px-8.lg\\:pl-72 > div > div > div > div.bg-white.p-4.shadow-md.col-span-1',
                    popover: {
                        title: 'Recommended Grants',
                        description: 'Our Ai analyses your organisation and matches you with relevant funds or use our filters to find opportunities',
                        nextBtnText: 'Alerts',
                        onNextClick: (element, step, opts) => {
                            localStorage.setItem('driverjs-current-step', String(opts.state.activeIndex + 1));
                            document.location.href = '/profile/alerts';
                        },
                    },
                },
                ...alerts,
                {
                    popover: {
                        title: 'FundinAI',
                        description: 'You have successfully completed the tour',
                        onNextClick: (element, step, opts) => {
                            localStorage.setItem('driverjs-completed', String(1));
                            driverObj.destroy();
                        },
                        doneBtnText: 'Finish tour',
                        showButtons: ['next'],
                        onPopoverRender: (popover, { config, state }) => {
                            const firstButton = document.createElement("button");
                            firstButton.innerText = "Book a demo";
                            popover.footerButtons.appendChild(firstButton);

                            firstButton.addEventListener("click", () => {
                                window.location.href = 'https://calendly.com/fundin/fundin-demo';
                            });
                        },
                    }
                },
            ],
            onNextClick: (element, step, opts) => {

                localStorage.setItem('driverjs-current-step', String(opts.state.activeIndex + 1));

                driverObj.moveNext();
            },
            onPrevClick: (element, step, opts) => {

                localStorage.setItem('driverjs-current-step', String(opts.state.activeIndex - 1));

                driverObj.movePrevious();
            },
            onCloseClick: (element, step, opts) => {
                localStorage.setItem('driverjs-completed', String(1));
                driverObj.destroy();
            },
        });
        const savedStep = localStorage.getItem('driverjs-current-step');
        if (savedStep) {
            driverObj.drive(Number(savedStep));
        } else {
            driverObj.drive();
        }
    }
}
