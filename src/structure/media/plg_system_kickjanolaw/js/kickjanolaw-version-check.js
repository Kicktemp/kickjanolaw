((Joomla, window, document) => {

  if (!Joomla) {
    throw new Error('core.js was not properly initialised');
  } // Handle the autocomplete

  checkVersion = ({
                    target
                  }) => {
    const userid = document.getElementById('jform_params_user_id');
    const shopid = document.getElementById('jform_params_shop_id');
    const version = document.getElementById('versionhtml');
    const url = target.getAttribute('data-url');

    if (userid.value.length > 1 && shopid.value.length > 1) {
      var postData = new FormData();
      postData.append('postuserid', userid.value);
      postData.append('postshopid', shopid.value);

      Joomla.request({
        url: `${url}`,
        data: postData,
        method: 'POST',
        perform: true,
        onSuccess: resp => {

          const html = resp;

          version.innerHTML = html;
        },
        onError: xhr => {
          if (xhr.status > 0) {
            Joomla.renderMessages(Joomla.ajaxErrorsMessages(xhr));
          }
        }
      });
    }
  };


  const onBoot = () => {
    const versionCheck = document.getElementById('versioncheckajax');
    versionCheck.addEventListener('click', checkVersion);

    document.removeEventListener('DOMContentLoaded', onBoot);
  };

  document.addEventListener('DOMContentLoaded', onBoot);
})(window.Joomla, window, document);
