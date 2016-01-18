function addJsonSelect () {
        
}

function request (requestUrl, method, data, callback) {
    $.ajax({
			url: requestUrl
			method: method,
			dataType: "json",
			data: data,
			success: function (res) {
				
			},
			error: function (xhr, ajaxOptions, thrownError) {
				
			}

		});
}