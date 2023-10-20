jQuery(document).ready(function ($) {
  const tyro = Tyro({
    liveMode: false,
  });
  $("body").on("updated_checkout", function () {
    init();
  });
  async function init() {
    console.log('init');
    // Create the Pay Request from your server and grab the Pay Secret
    // for demonstration purposes we are automatically
    // generating a demo pay secret for testing only
    let paySecret;
    try {
      const response = await fetch('https://api.tyro.com/connect/pay/demo/requests', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ test: true }),
      });
      const responseData = await response.json();
      console.log(responseData);
      paySecret = responseData.paySecret;
    } catch (error) {
      setErrorMessage(error);
      return;
    }
  
    // Initialize the pay secret
    try {
      await tyro.init(paySecret);
    } catch (error) {
      setErrorMessage(error);
      return;
    }
  
    // Embed your theme or custom styles here
    try {
      const payForm = tyro.createPayForm({
        theme: 'default',
        styleProps: {
          bodyPadding: 0,
          bodyMinWidth: '200px',
          bodyMaxWidth: '1080px',
          labelPosition: 'block',
          inputSpacing: '0',
          inputPadding: '10',
          labelPadding: '6 0',
          labelFontColor: '#222',
          showSupportedCards: true,
          allowSupportedCards: ['visa', 'amex'],
        },
        options: {
          applePay: {
            enabled: true,
          },
          googlePay: {
            enabled: true,
          },
        },
      });
      // Attach the Wallet Listeners to the form
      payForm.setWalletPaymentBeginListener((paymentType) => {
        console.log('setWalletPaymentBeginListener');
        setSubmittingOverlay(true);
        if(paymentType === 'APPLE_PAY') {
          // optionally do something specific to Apple Pay
        } else if(paymentType === 'GOOGLE_PAY'){
          // optionally do something specific to Google Pay
        }
      });
      payForm.setWalletPaymentCancelledListener((paymentType) => {
        setSubmittingOverlay(false);
        if(paymentType === 'APPLE_PAY') {
          // optionally do something specific to Apple Pay
        } else if(paymentType === 'GOOGLE_PAY'){
          // optionally do something specific to Google Pay
        }
      });
      payForm.setWalletPaymentCompleteListener((paymentType, error) => {
        console.log('setWalletPaymentCompleteListener');
        if(error) {
          setErrorMessage(error);
          setSubmittingOverlay(false);
        } else {
          getPaymentResult();
        }
      });
      // Attach the Payform into your websites element
      payForm.inject('#tyro-pay-form');
    } catch (error) {
      setErrorMessage(error);
      return;
    }
  
    const payBtn = document.getElementById('pay-form-submit');
    if (payBtn) {
      payBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
          setErrorMessage(false);
          setSubmitLoading(true);
          // Submit the payment
          console.log('Submit the payment');
          const result = await tyro.submitPay();
          getPaymentResult();
        } catch (error) {
          setSubmitLoading(false);
          if (error?.type === 'CLIENT_VALIDATION_ERROR' && !error?.errorCode) {
            // can ignore these errors as handled by validation
          } else {
            // display other errors
            setErrorMessage(error);
          }
        }
      });
    }
  
    // Set up the canvas now that everything is ready, remove the loading states
    // document.getElementById('loading-state').style.display = 'none';
    // document.getElementById('pay-form-submitting-overlay').style.display = 'none';
    // document.getElementById('pay-form').style.display = '';
  }
  
  async function getPaymentResult() {
    const payRequest = await tyro.fetchPayRequest();
    // display result
    if (payRequest.status === 'SUCCESS') {
      setSuccess();
    } else {
      console.log(payRequest);
    }
    setSubmittingOverlay(false);
  }
  
  //Below are helpers for handling display and reactions
  function setSuccess() {
    const eleForm = document.getElementById('pay-form');
    eleForm.style.display = 'none';
    const eleSuccess = document.getElementById('success-message');
    eleSuccess.style.display = 'block';
  }
  
  function setSubmittingOverlay(state) {
    const eleForm = document.getElementById('pay-form-submitting-overlay');
    eleForm.style.display = state ? 'block' : 'none';
  }
  
  function setErrorMessage(error) {
    const message = error?.errorCode ? `(${error?.errorCode}) ${error?.message}` : `${error.toString()}`;
    const ele = document.getElementById('error-message');
    ele.innerHTML = message;
    ele.style.display = error ? 'block' : 'none';
    if (message) {
      window.scrollTo(0, 0);
    }
  }
  
  function setSubmitLoading(state) {
    const ele = document.getElementById('pay-form-submit');
    ele.className = ele.className.replace(' loading', '');
    if (state === true) {
      ele.className = ele.className + ' loading';
    }
    ele.disabled = !!state;
  }
});
