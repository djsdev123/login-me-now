import { __ } from "@wordpress/i18n";
import { useLocation } from "react-router-dom";
import ProModules from "@DashboardApp/pages/welcome/ProModules";
import apiFetch from '@wordpress/api-fetch';
import VideoPopup from "./VideoPopup";
import { useState } from "react";
import { useSelector, useDispatch } from 'react-redux';
import MagicLinkPopup from "./MagicLinkPopup";

const classNames = (...classes) => classes.filter(Boolean).join(" ");

const Welcome = () => {
	const query = new URLSearchParams(useLocation()?.search);
	const dispatch = useDispatch();

	const allowAutoPlay =
		"1" === query.get("login-me-now-activation-redirect") ? 1 : 0;

	const onGenerateToken = (e) => {
		e.preventDefault();
		e.stopPropagation();

		const formData = new window.FormData();
		formData.append('action', 'login_me_now_generate_token');
		formData.append('security', lmn_admin.generate_token_nonce);
		e.target.innerText = lmn_admin.generating_token_text;

		apiFetch({
			url: lmn_admin.ajax_url,
			method: 'POST',
			body: formData,
		}).then((data) => {
			if (data.success) {
				dispatch( {type: 'GENERATE_MAGIC_LINK_POPUP', payload: __( 'Magic Link Created Successfully', 'login-me-now' ) } );

				e.target.innerText = data.data.magic_number;

				console.log(data);
				// window.open(lmn_admin.login_me_now_base_url, '_self');
			}
		});

		console.log(e)

		// window.open(lmn_admin.extension_url, "_blank");
	};

	const getLoginMeNowProTitle = () => {
		return lmn_admin.pro_installed_status ? __('Activate Now', 'login-me-now') : __('Upgrade Now', 'login-me-now');
	}

	const onGetLoginMeNowPro = (e) => {
		e.preventDefault();
		e.stopPropagation();

		if (lmn_admin.pro_installed_status) {
			const formData = new window.FormData();
			formData.append('action', 'login_me_now_recommended_plugin_activate');
			formData.append('security', lmn_admin.plugin_manager_nonce);
			formData.append('init', 'login-me-now-addon/login-me-now-addon.php');
			e.target.innerText = lmn_admin.plugin_activating_text;

			apiFetch({
				url: lmn_admin.ajax_url,
				method: 'POST',
				body: formData,
			}).then((data) => {
				if (data.success) {
					window.open(lmn_admin.login_me_now_base_url, '_self');
				}
			});
		} else {
			window.open(
				lmn_admin.upgrade_url,
				'_blank'
			);
		}
	};

	const [videoPopup, setVideoPopup] = useState(false);

	const toggleVideoPopup = () => {
		setVideoPopup(!videoPopup);
	};

	return (<>
		<MagicLinkPopup/>
		<p>
		</p>
		<main className="py-[2.43rem]">
			<div className="max-w-3xl mx-auto px-6 lg:max-w-7xl">
				<h1 className="sr-only"> Login Me Now </h1>

				{/* Banner section */}
				{lmn_admin.show_self_branding && (
					<div className="grid grid-cols-1 gap-4 items-start lg:grid-cols-5 lg:gap-0 xl:gap-0 rounded-md bg-white overflow-hidden shadow-sm px-8 py-8">
						<div className="grid grid-cols-1 gap-4 lg:col-span-3 h-full md:mr-[5.25rem]">
							<section aria-labelledby="section-1-title h-full">
								<h2 className="sr-only" id="section-1-title">
									Welcome Banner
								</h2>
								<div className="flex flex-col justify-center h-full">
									<div className="">
										<p className="pb-4 font-medium text-base text-slate-800">
											{__("Hello ", "login-me-now") +
												lmn_admin.current_user +
												","}
										</p>
										<div className="flex">
											<h2 className="text-slate-800 text-[2rem] leading-10 pb-3 font-semibold text-left">
												{__(
													`Welcome to ${lmn_admin.product_name}`,
													"login-me-now"
												)}
											</h2>
											{lmn_admin.pro_available ? (
												<span className="ml-2 h-full inline-flex leading-[1rem] font-medium flex-shrink-0 py-[0rem] px-1.5 text-[0.625rem] text-white bg-slate-800 border border-slate-800 rounded-[0.1875rem] -tablet:mt:10">
													{__('PRO', 'login-me-now')}
												</span>)
												:
												(<span className="ml-2 h-full inline-flex leading-[1rem] flex-shrink-0 py-[0rem] px-1.5 text-[0.625rem] text-lmn bg-blue-50 border border-blue-50 rounded-[0.1875rem] font-medium -tablet:mt:10">
													{__('FREE', 'login-me-now')}
												</span>)
											}
										</div>

										<p className="text-base leading-[1.625rem] text-slate-600 pb-7">
											{__(
												`${lmn_admin.product_name} is fast, fully customizable & beautiful WordPress theme suitable for blog, personal portfolio, business website and WooCommerce storefront. It is very lightweight and offers unparalleled speed.`,
												"login-me-now"
											)}
										</p>

										<span className="relative z-0 inline-flex flex-col sm:flex-row justify-start w-full">
											<button
												type="button"
												className="sm:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-lmn focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none mr-4 mb-2 sm:mb-0"
												onClick={onGenerateToken}
											>
												{__(
													"Generate Magic Number",
													"login-me-now"
												)}
											</button>
										</span>
									</div>
								</div>
							</section>
						</div>

						<div className="grid grid-cols-1 gap-4 lg:col-span-2 h-full">
							<div className="login-me-now-video-container">
								{/* Added rel=0 query paramter at the end to disable YouTube recommendations */}
								<iframe
									className="login-me-now-video rounded-md"
									src={`https://www.youtube-nocookie.com/embed/uBNUpyCM8G8?showinfo=0&autoplay=${allowAutoPlay}&mute=${allowAutoPlay}&rel=0`}
									allow="autoplay"
									title="YouTube video player"
									frameBorder="0"
									allowFullScreen
								></iframe>
							</div>
						</div>
					</div>
				)}

				{/* Left Column */}
				<div className="grid grid-cols-1 gap-[32px] items-start lg:grid-cols-3 lg:gap-[32px] xl:gap-[32px] mt-[32px]">
					{/* Left column */}
					<div
						className={classNames(
							lmn_admin.show_self_branding
								? "lg:col-span-2"
								: "lg:col-span-3",
							"grid grid-cols-1 gap-[32px]"
						)}
					>

						<section aria-labelledby="section-1-title h-full">
							<h2 className="sr-only" id="section-1-title">
								Do more with Login Me Now Pro Features
							</h2>
							<div className="p-[2rem] rounded-md bg-white overflow-hidden shadow-sm flex flex-col justify-center h-full">
								<div className="relative w-full flex flex-col sm:flex-row sm:items-center sm:justify-between">
									<span className="font-semibold text-xl leading-6 text-slate-800 mb-4 sm:mb-0">
										{lmn_admin.pro_available
											? __(
												`${lmn_admin.plugin_name} Features`,
												"login-me-now"
											)
											: __(
												`Do more with ${lmn_admin.plugin_name} Features`,
												"login-me-now"
											)}
									</span>
									{!lmn_admin.pro_available && (
										<a
											className="lg:flex-shrink-0 text-sm font-medium text-lmn focus:text-lmn focus-visible:text-lmn-hover active:text-lmn-hover hover:text-lmn-hover no-underline"
											href={lmn_admin.upgrade_url}
											target="_blank"
											rel="noreferrer"
											onClick={onGetLoginMeNowPro}
										>
											{" "}
											{getLoginMeNowProTitle()}{" "}
										</a>
									)}
								</div>

								{wp.hooks.applyFilters(
									`login_me_now_dashboard.pro_extensions`,
									<ProModules />
								)}
							</div>
						</section>

						<section aria-labelledby="section-1-title h-full">
							<h2 className="sr-only" id="section-1-title">
								Your License
							</h2>
							<div className="ast-welcome-screen rounded-md bg-white overflow-hidden shadow-sm flex flex-col justify-center h-full">
								{wp.hooks.applyFilters(
									`login_me_now_dashboard.welcome_screen_after_integrations`,
									<span />
								)}
							</div>
						</section>
					</div>

					{/* Right Column */}
					{lmn_admin.show_self_branding && (
						<div className="grid grid-cols-1 gap-[32px]">
							<section aria-labelledby="section-2-title">
								<h2 className="sr-only" id="section-2-title">
									Priority Support
								</h2>
								<div className="relative box-border border border-sky-500 rounded-md bg-white shadow-sm overflow-hidden transition hover:shadow-hover">
									<div className="p-6">
										<h3 className="relative flex items-center text-slate-800 text-base font-semibold pb-2">
											<span className="flex-1">
												{__(
													"Priority Support",
													"login-me-now"
												)}
											</span>
											<span className="text-[0.625rem] leading-[1rem] font-medium text-white bg-slate-800 border border-slate-800 rounded-[0.1875rem] relative inline-flex flex-shrink-0 py-[0rem] px-1.5 self-start">
												{__("PRO", "login-me-now")}
											</span>
										</h3>
										<p className="text-slate-500 text-sm pb-5 pr-12">
											{__(
												"We aim to answer all priority support requests within 2-3 hours.",
												"login-me-now"
											)}
										</p>
										<a
											className="text-sm text-lmn focus:text-lmn focus-visible:text-lmn-hover active:text-lmn-hover hover:text-lmn-hover no-underline"
											href="https://halalbrains.com/support/?utm_source=wp&utm_medium=dashboard"
											target="_blank"
											rel="noreferrer"
										>
											{__("Learn More →", "login-me-now")}
										</a>
									</div>
								</div>
							</section>

							<section aria-labelledby="section-2-title">
								<h2 className="sr-only" id="section-2-title">
									Rate Us
								</h2>
								<div className="box-border rounded-md bg-white shadow-sm overflow-hidden transition hover:shadow-hover">
									<div className="p-6">
										<h3 className="text-slate-800 text-base font-semibold pb-2">
											{__("Rate Us", "login-me-now")}
										</h3>
										<p className="text-slate-500 text-sm pb-2.5 pr-12 flex items-center">
											<span className="text-xl text-slate-800 flex mr-2">
												{[1, 2, 3, 4, 5].map((item, key) => (
													<svg key={key} width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
														<g clipPath="url(#clip0_2358_55923)">
															<path d="M9.04894 2.92705C9.3483 2.00574 10.6517 2.00574 10.9511 2.92705L12.0206 6.21885C12.1545 6.63087 12.5385 6.90983 12.9717 6.90983H16.4329C17.4016 6.90983 17.8044 8.14945 17.0207 8.71885L14.2205 10.7533C13.87 11.0079 13.7234 11.4593 13.8572 11.8713L14.9268 15.1631C15.2261 16.0844 14.1717 16.8506 13.388 16.2812L10.5878 14.2467C10.2373 13.9921 9.7627 13.9921 9.41221 14.2467L6.61204 16.2812C5.82833 16.8506 4.77385 16.0844 5.0732 15.1631L6.14277 11.8713C6.27665 11.4593 6.12999 11.0079 5.7795 10.7533L2.97933 8.71885C2.19562 8.14945 2.59839 6.90983 3.56712 6.90983H7.02832C7.46154 6.90983 7.8455 6.63087 7.97937 6.21885L9.04894 2.92705Z" fill="#334155" />
														</g>
														<defs>
															<clipPath id="clip0_2358_55923">
																<rect width="20" height="20" fill="white" />
															</clipPath>
														</defs>
													</svg>
												))}

											</span>
											<span className="text-xs leading-4 align-text-bottom text-slate-400">
												{" "}
												{__(
													"Based on 5k+ reviews",
													"login-me-now"
												)}{" "}
											</span>
										</p>
										<p className="text-slate-500 text-sm pb-5">
											{__(
												"We love to hear from you, we would appreciate every single review.",
												"login-me-now"
											)}
										</p>
										<a
											className="text-sm text-lmn focus:text-lmn focus-visible:text-lmn-hover active:text-lmn-hover hover:text-lmn-hover no-underline"
											href="https://wordpress.org/support/plugin/login-me-now/reviews/?rate=5#new-post"
											target="_blank"
											rel="noreferrer"
										>
											{__("Submit a Review →", "login-me-now")}
										</a>
									</div>
								</div>
							</section>
						</div>
					)}
				</div>
			</div>
			<VideoPopup allowAutoPlay={allowAutoPlay} videoPopup={videoPopup} toggleVideoPopup={toggleVideoPopup} />
		</main>
	</>
	);
};

export default Welcome;
