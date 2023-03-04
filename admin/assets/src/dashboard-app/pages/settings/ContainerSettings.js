import { __ } from '@wordpress/i18n';
import { useSelector } from 'react-redux';

import Logs from './general/Logs';
import LogsExpiration from './general/LogsExpiration';
import OnetimeLinks from './access-links/OnetimeLinks';
import OnetimeLinksExpiration from './access-links/OnetimeLinksExpiration';
import ReusableLinksExpiration from './access-links/ReusableLinksExpiration';
import ReusableLinks from './access-links/ReusableLinks';

function SettingsWrapper({ state }) {
	const wrappers = wp.hooks.applyFilters(
		'login_me_now_dashboard.settings_tab_wrappers',
		{
			'global-settings': <> <Logs /> <LogsExpiration /> </>,
			'access-links': <> <OnetimeLinks/> <OnetimeLinksExpiration/> <ReusableLinks /> <ReusableLinksExpiration /></>,
		}
	);
	return <div>{wrappers[state]}</div>;
}

const ContainerSettings = () => {

	const activeSettingsNavigationTab = useSelector((state) => state.activeSettingsNavigationTab);

	// Parent Div is Required to add Padding to the Entire Structure for Smaller Windows.
	return (
		<>
			<div className='lg:col-span-9 border-l'>
				{wp.hooks.applyFilters(`login_me_now_dashboard.settings_screen_before_${activeSettingsNavigationTab}`, <span />)}
				<SettingsWrapper state={activeSettingsNavigationTab}></SettingsWrapper>
				{wp.hooks.applyFilters(`login_me_now_dashboard.settings_screen_after_${activeSettingsNavigationTab}`, <span />)}
			</div>
		</>
	);
};

export default ContainerSettings;
