function SwalMessage(message,type){
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      });
      
      Toast.fire({
        icon: type,
        title: message
      })
}