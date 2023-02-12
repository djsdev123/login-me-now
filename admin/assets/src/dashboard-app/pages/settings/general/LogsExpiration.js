import { useSelector } from 'react-redux';
import { useState } from 'react'
import { Listbox } from '@headlessui/react'

const expirationOptions = [
  { id: 7, name: 'for 7 Days' },
  { id: 30, name: 'for 30 Days' },
  { id: 60, name: 'for 60 Days' },
  { id: 90, name: 'for 90 Days' },
]

const LogsExpiration = () => {

  const updateLogsExpiration = () => {

		let assetStatus;
		if (enableLogs === false) {
			assetStatus = true;
		} else {
			assetStatus = false;
    }

    dispatch({ type: 'UPDATE_ENABLE_LOGS', payload: assetStatus });

    const formData = new window.FormData();

    formData.append('action', 'login_me_now_update_admin_setting');
    formData.append('security', lmn_admin.update_nonce);
    formData.append('key', 'logs');
    formData.append('value', assetStatus);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: 'POST',
      body: formData,
    }).then(() => {
      dispatch({ type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __('Successfully saved!', 'login-me-now') });
    });
  };

  const enableLogs = useSelector((state) => state.enableLogs);
	const enableLogsStatus = false === enableLogs ? false : true;

	const [selectedPerson] = useState(expirationOptions[0])
  return (
    <section className={`login-me-now-dep-field-${enableLogsStatus} text-sm block border-b border-solid border-slate-200 px-8 py-8 justify-between`}>
			<div className='mr-16 w-full flex items-center'></div>
   
      <Listbox value={enableLogsStatus} onChange={updateLogsExpiration}>
        <Listbox.Button>{selectedPerson.name}</Listbox.Button>
        <Listbox.Options className='bg-slate-10'>
          {expirationOptions.map((option) => (
            <Listbox.Option 
              key={option.id} 
              value={option} 
              className='text-sm text-slate-500 relative cursor-pointer select-none py-1 pr-1 mb-1'
              >
              {option.name}
            </Listbox.Option>
          ))}
        </Listbox.Options>
    </Listbox>

    </section>
  )
}

export default LogsExpiration;