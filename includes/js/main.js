$(document).ready(function(){
	$('#submitBtn').click(function(event) {
		if($.trim($('#appid').val()) == ''){
			$('#appid').parent().addClass('has-error')
			return false
		}

		if($.trim($('#appsecret').val()) == ''){
			$('#appsecret').parent().addClass('has-error')
			return false
		}

		initStatus()

		$submitBtn = $(this)
		$submitBtn.attr('disabled', 'disabled')

		$submitBtn.text('上传Excel中...')
		$('.status').find('.progress').eq(0).fadeIn()
		$('.stepline').find('.help-block').eq(0).fadeIn()
		//异步上传excel文件
		$.ajaxFileUpload({
			url: 'includes/ajax/ajax_upload.php',
			fileElementId: 'excelFile',
			dataType: 'json',
			success: function(data, status){
				if(data.code == 1){
					$submitBtn.text('备份旧菜单...')
					$('.status').find('.progress').eq(0).hide()
					$('.status').find('.progress').eq(1).show()
					$('.stepline').find('.help-block').eq(0).append('&nbsp;&nbsp;&nbsp;<span>成功</span>')
					$('.stepline').find('.help-block').eq(0).parent().removeClass('alert-info')
					$('.stepline').find('.help-block').eq(0).parent().addClass('alert-success')
					$('.stepline').find('.help-block').eq(1).fadeIn()

					$.post(
						'includes/ajax/ajax_backupMenu.php',
						{
							appid: $('#appid').val(),
							appsecret: $('#appsecret').val()
						},
						function(data){
							data = eval('('+data+')')
							if(data.code == 1){
								$submitBtn.text('更新菜单...')
								$('.status').find('.progress').eq(1).hide()
								$('.status').find('.progress').eq(2).show()
								$('.stepline').find('.help-block').eq(1).append('&nbsp;&nbsp;&nbsp;<span>成功</span>')
								$('.stepline').find('.help-block').eq(1).parent().removeClass('alert-info')
								$('.stepline').find('.help-block').eq(1).parent().addClass('alert-success')
								$('.stepline').find('.help-block').eq(2).fadeIn()

								$.post(
									'includes/ajax/ajax_createMenu.php',
									{
										appid: $('#appid').val(),
										appsecret: $('#appsecret').val()
									},
									function(data){
										data = eval('('+data+')')
										// console.log(data.errmsg)
										if(data.errcode == 0){
											$('.status').find('.progress').eq(2).hide()
											$('.status').find('.progress').eq(3).show()
											$('.status').find('.progress').eq(3).hide()
											$('.status').find('.progress').eq(4).show()
											$('.stepline').find('.help-block').eq(2).append('&nbsp;&nbsp;&nbsp;<span>成功</span>')
											$('.stepline').find('.help-block').eq(2).parent().removeClass('alert-info')
											$('.stepline').find('.help-block').eq(2).parent().addClass('alert-success')
											$('#successMsg').slideDown('400')
											$submitBtn.text('更新菜单')
											$submitBtn.removeAttr('disabled')
										}else{
											$('.stepline').find('.help-block').eq(2).append('&nbsp;&nbsp;&nbsp;<span>失败: '+data.errmsg+'</span>')
											$('.stepline').find('.help-block').eq(2).parent().removeClass('alert-info')
											$('.stepline').find('.help-block').eq(2).parent().addClass('alert-danger')
											$submitBtn.text('更新菜单')
											$submitBtn.removeAttr('disabled')
										}
									}
									)
							}
						}
						)
				}else{
					data = eval('('+data+')')

					$('.stepline').find('.help-block').eq(0).append('&nbsp;&nbsp;&nbsp;<span>Error:  Excel格式有问题，请检查Excel文件类型即内容格式</span>')
					$('.stepline').find('.help-block').eq(0).parent().removeClass('alert-info')
					$('.stepline').find('.help-block').eq(0).parent().addClass('alert-danger')
					$submitBtn.text('更新菜单')
					$submitBtn.removeAttr('disabled')

					
				}
			},
			error: function(data, status, e){
				//此处返回的data为详细信息，此处需做处理
				data = eval('('+data.responseText+')')
				// if(data.code == -1){
				// 	$('.stepline').find('.help-block').eq(0).append('&nbsp;&nbsp;&nbsp;<span>Error: '+data.msg+'</span>')
				// }
				$('.stepline').find('.help-block').eq(0).append('&nbsp;&nbsp;&nbsp;<span>Error:  Excel格式有问题，请检查Excel文件类型即内容格式</span>')
				$('.stepline').find('.help-block').eq(0).parent().removeClass('alert-info')
				$('.stepline').find('.help-block').eq(0).parent().addClass('alert-danger')
				$submitBtn.text('更新菜单')
				$submitBtn.removeAttr('disabled')
			}
		})

		return false
	});

	$('#appid, #appsecret').click(function(event) {
		$('#excelForm').find('.has-error').each(function(index, el) {
			$(this).removeClass('has-error')
		});
	});

})

function initStatus(){
	//初始化各种状态
	$('.stepline').html('<div alert alert-info><span class="help-block" style="display: none">1、 正在验证并上传Excel文件...</span></div><div alert alert-info><span class="help-block" style="display: none">2、 正在备份当前菜单...</span></div><div alert alert-info><span class="help-block" style="display: none">3、 正在更新菜单...</span></div>')
	$('.status').find('.progress').each(function(){
		$(this).hide()
	})
	$('#successMsg').hide()
}