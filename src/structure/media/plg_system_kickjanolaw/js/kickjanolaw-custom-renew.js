((Joomla, window, document) => {

  if (!Joomla) {
    throw new Error('core.js was not properly initialised');
  } // Handle the autocomplete

  customRenew = ({
                    target
                  }) => {
    const language = document.getElementById('language');
    const type = document.getElementById('jform_params_shop_id');
    const RenewSuccess = document.getElementById('RenewSuccess');
    const RenewError = document.getElementById('RenewError');
    const url = target.getAttribute('data-url');

    if (language.value.length > 1 && type.value.length > 1) {
      var postData = new FormData();
      postData.append('postlanguage', language.value);
      postData.append('posttype', type.value);

      Joomla.request({
        url: `${url}`,
        data: postData,
        method: 'POST',
        perform: true,
        onSuccess: resp => {
          RenewError.classList.add('hidden');
          RenewSuccess.classList.remove('hidden');
        },
        onError: xhr => {
          RenewSuccess.classList.add('hidden');
          RenewError.classList.remove('hidden');
          if (xhr.status > 0) {
            Joomla.renderMessages(Joomla.ajaxErrorsMessages(xhr));
          }
        }
      });
    }
  };


  const onBoot = () => {
    const renewButton = document.getElementById('cleancachefolder');
    renewButton.addEventListener('click', customRenew);

    document.removeEventListener('DOMContentLoaded', onBoot);
  };

  document.addEventListener('DOMContentLoaded', onBoot);
})(window.Joomla, window, document);
