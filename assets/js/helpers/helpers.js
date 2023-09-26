/**
 *
 * Script com funções utilizadas
 *
 * @author Emprezaz.com
 *
 **/

(function ($, Helpers) {

	var searchTable = function () {

		$('input[name="search"]').keyup(function () {

			var search = $(this).val().toLowerCase();

			$('table tbody tr').hide();

			if (search == '' || search == "r$0,00") {
				$('table tbody tr').show();
				return;
			}

			$($('table tbody td')).each(function () {

				if ($(this).html() != '') {
					if ($(this).html().toLowerCase().indexOf(search) != -1) {
						$(this).parent().show();
					} else if ($(this).html().indexOf(search) != -1) {
						$(this).parent().show();
					}
				}

			});

		})

	}

	var searchDropdown = function () {

		$('input[name="search"]').keyup(function () {

			var search = $(this).val().toLowerCase();

			$('.dropdown .dropdown-menu .dropdown-item').hide();

			if (search == '') {
				$('.dropdown .dropdown-menu .dropdown-item').show();
				return;
			}

			$($('.dropdown .dropdown-menu .dropdown-item')).each(function () {

				if ($(this).text() != '') {
					if ($(this).text().toLowerCase().indexOf(search) != -1) {
						$(this).show();
					} else if ($(this).text().indexOf(search) != -1) {
						$(this).show();
					}
				}

			});

		})

	}

	var getPage = function () {

		var split = window.location.href.split('/');
		var last = split.length - 1;
		var page = split[last];

		if (isNaN(page)) {
			return page;
		}

		return split[last - 2] + '/' + split[last - 1];

	};

	var isValidEmailAddress = function (emailAddress) {
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		return pattern.test(emailAddress);
	};

	var datepicker = function (input) {

		input.daterangepicker({
			singleDatePicker: true,
			locale: {
				format: 'DD/MM/YYYY',
			},
			minYear: 1900,
		});

	};

	var phoneMask = function (phone) {

		var phone = phone || $('#phone');
		var mask = '(00) 00000-0000';

		phone.mask(mask);
		phone.on('keyup', function () {

			var number = $(this).val()[5];

			if (number != 9) {
				phone.mask('(00) 0000-0000');
			} else {
				phone.mask(mask);
			}

		});

	};

	var username = function (username) {

		username.keyup(function () {
			usernameValue()
		})
		username.click(function () {
			usernameValue()
		})
		username.focus(function () {
			usernameValue()
		})
		username.blur(function () {
			usernameValue()
		})

		function usernameValue() {
			let v = username.val()
			v = v.replace(/[^ABCDEFGHIJKLMONOPQRESTUVXWYZabcdefghijklmnopqrstuvxwyz0123456789@.]/g, "")
			v = v.replace(/A/gi, "a")
			v = v.replace(/B/gi, "b")
			v = v.replace(/C/gi, "c")
			v = v.replace(/D/gi, "d")
			v = v.replace(/E/gi, "e")
			v = v.replace(/F/gi, "f")
			v = v.replace(/G/gi, "g")
			v = v.replace(/H/gi, "h")
			v = v.replace(/I/gi, "i")
			v = v.replace(/J/gi, "j")
			v = v.replace(/K/gi, "k")
			v = v.replace(/L/gi, "l")
			v = v.replace(/M/gi, "m")
			v = v.replace(/N/gi, "n")
			v = v.replace(/O/gi, "o")
			v = v.replace(/P/gi, "p")
			v = v.replace(/Q/gi, "q")
			v = v.replace(/R/gi, "r")
			v = v.replace(/S/gi, "s")
			v = v.replace(/T/gi, "t")
			v = v.replace(/U/gi, "u")
			v = v.replace(/V/gi, "v")
			v = v.replace(/X/gi, "x")
			v = v.replace(/W/gi, "w")
			v = v.replace(/Y/gi, "y")
			v = v.replace(/Z/gi, "z")
			username.val(v)
		}

	}

	var dateMask = function (date) {
		var date = date || $('#ageDate');
		var mask = '00/00/0000';
		date.mask(mask);
	};

	var timeMask = function (time) {
		var time = time || $('.time');
		var mask = '00:00';
		time.mask(mask);
	};

	var cpfMask = function (cpf) {
		var cpf = cpf || $('#cpf');
		var mask = '000.000.000-00';
		cpf.mask(mask);
	};

	var cpfTest = function (strCPF) {

		var strCPF = strCPF.replace(".", "").replace(".", "").replace("-", "");

		var Soma;
		var Resto;
		Soma = 0;
		if (strCPF == "00000000000") return false;

		for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
		Resto = (Soma * 10) % 11;

		if ((Resto == 10) || (Resto == 11)) Resto = 0;
		if (Resto != parseInt(strCPF.substring(9, 10))) return false;

		Soma = 0;
		for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
		Resto = (Soma * 10) % 11;

		if ((Resto == 10) || (Resto == 11)) Resto = 0;
		if (Resto != parseInt(strCPF.substring(10, 11))) return false;
		return true;

	}

	var loadCep = function (cep) {

		resetAddress();
		$.ajax({
			url: 'https://viacep.com.br/ws/' + cep + '/json/',
			dataType: 'JSON',

			complete: function (response) {
				if (response.status === 200) {

					addAddress(response.responseJSON);

				}
			}
		});

	};

	var loadShippingCep = function (cep) {

		resetShippingAddress();
		$.ajax({
			url: 'https://viacep.com.br/ws/' + cep + '/json/',
			dataType: 'JSON',

			complete: function (response) {
				if (response.status === 200) {

					addShippingAddress(response.responseJSON);

				}
			}
		});

	};

	var cnpjMask = function (cnpj) {

		var cnpj = cnpj || $('#cnpj');
		var mask = '00.000.000/0000-00';
		cnpj.mask(mask);

	};

	var cpfcnpjMask = function (documentUser) {

		var documentUser = documentUser || $('#accountdocument');
		var mask = '000.000.000-000';
		documentUser.mask(mask);

		documentUser.on('keyup', function () {

			var number = $(this).val().length;

			if (number > 14) {
				documentUser.mask('00.000.000/0000-00');
			} else {
				documentUser.mask(mask);
			}

		});
	};

	var cepMask = function (cep) {

		var cep = cep || $('#cep');
		var mask = '00000-000';
		cep.mask(mask);

	};

	var rgMask = function (rg) {

		var rg = rg || $('#rg');
		var mask = '0.000.0000';
		rg.mask(mask);

	};

	var numberMask = function (number) {

		var number = number || $('#number');
		var mask = '00000000000';
		number.mask(mask);

	};

	var cardnumberMask = function (cardnumber) {

		var cardnumber = cardnumber || $('#cardnumber');
		var mask = '0000 0000 0000 0000';
		cardnumber.mask(mask);

	}

	var checkNumberCard = function (cardnumber) {

		if (cardnumber == "0000 0000 0000 0000" ||
			cardnumber == "1111 1111 1111 1111" ||
			cardnumber == "2222 2222 2222 2222" ||
			cardnumber == "3333 3333 3333 3333" ||
			cardnumber == "4444 4444 4444 4444" ||
			cardnumber == "5555 5555 5555 5555" ||
			cardnumber == "6666 6666 6666 6666" ||
			cardnumber == "7777 7777 7777 7777" ||
			cardnumber == "8888 8888 8888 8888" ||
			cardnumber == "9999 9999 9999 9999"
		) {
			return false;
		}

		return true;

	}

	var numberMonthMask = function (numberMonth) {

		var numberMonth = numberMonth || $('#numberMonth');
		var mask = '00';
		numberMonth.mask(mask);

	}

	var cardValidateMask = function (validate) {

		var validate = validate || $('#validate-card');
		var mask = '00/0000';
		validate.mask(mask);

	}
	var numberYearMask = function (numberYear) {

		var numberYear = numberYear || $('#numberYear');
		var mask = '0000';
		numberYear.mask(mask);

	}

	var cvvMask = function (cvv) {

		var cvv = cvv || $('#cvv');
		var mask = '000';
		cvv.mask(mask);

	}

	var codeMask = function (code) {

		var code = code || $('#code');
		var mask = '0000';
		code.mask(mask);

	};

	var heigthMask = function (heigth) {

		var heigth = heigth || $('#heigth');
		var mask = '0.00m';
		heigth.mask(mask);

	};

	// var weightMask = function(weight){

	// 	var weight = weight || $('#weight');
	// 	var mask  = '00 Kg';
	// 	weight.mask(mask);

	// };		

	var addAddress = function (address) {

		if (address.erro) {

			return;

		}

		$('#state').prop("value", address.uf);
		$('#city').prop("value", address.localidade);
		$('#neighborhood').prop("value", address.bairro);
		$('#street').prop("value", address.logradouro);

	};

	var addShippingAddress = function (address) {

		if (address.erro) {

			return;

		}

		$('.shipping-uf').prop("value", address.uf);
		$('.shipping-city').prop("value", address.localidade);
		$('.shipping-neighborhood').prop("value", address.bairro);
		$('.shipping-street').prop("value", address.logradouro);

	};

	var moneyMask = function (value) {

		value.maskMoney({
			prefix: "R$",
			decimal: ",",
			thousands: "."
		});

	}

	var resetAddress = function () {

		$('.address-input').val('');

	};

	var resetShippingAddress = function () {

		$('.shipping-address-input').val('');

	};

	var validateForm = function (form, callback) {

		// copiado apenas para teste, necessario fazer uma nova validação

		validate = true;
		form.find('input.rneed, select.rneed option:selected').each(function (i) {

			item = $(this);

			parent = item.parents('.form-group');
			name = item.parents('.form-group').find('label').text();

			if (item.val() == '' || item.val() == null || item.val() == undefined && item.attr('type') != 'radio' && item.attr('type') != 'checkbox') {
				swal.fire({
					type: 'warning',
					title: 'Preencha o campo!',
					text: 'Campo vazio: ' + name,
					timer: 3500,
					showConfirmButton: false,
					onClose: function () {
						item.focus();
					}
				});
				validate = false;

			}


			if (validate == false) {
				return false;
			}

		}).promise().done(function () {

			if (validate) {
				callback();
				return validate;
			}

		});

		return validate;

	}

	var coinMask = function (coin) {
		return coin.maskMoney();
	}

	var ftMask = function (weight) {
		weight.maskMoney({
			suffix: " ft",
			decimal: ",",
			thousands: "."
		});
	};
	var mMask = function (weight) {
		weight.maskMoney({
			suffix: " m",
			decimal: ",",
			thousands: "."
		});
	};
	var cmMask = function (weight) {
		weight.maskMoney({
			suffix: " cm",
			decimal: ",",
			thousands: "."
		});
	};
	var cmIntMask = function (weight) {
		weight.maskMoney({
			suffix: " cm",
			decimal: "",
			thousands: "."
		});
	};
	var kgMask = function (weight) {
		weight.maskMoney({
			suffix: " kg",
			decimal: ",",
			thousands: "."
		});
	};
	var gMask = function (weight) {
		weight.maskMoney({
			suffix: " g",
			decimal: ",",
			thousands: "."
		});
	};
	var ptsMask = function (weight) {
		weight.maskMoney({
			suffix: " pts",
			decimal: ",",
			thousands: "."
		});
	}
	var temperatureMask = function (temperature) {
		temperature.maskMoney({
			suffix: " ºC",
			decimal: ",",
			thousands: "."
		});
	};

	var landlineMask = function (phone) {
		phone.maskMoney({
			prefix: "+",
			decimal: "",
			thousands: ""
		});
	};

	Helpers.validateForm = validateForm;
	Helpers.getPage = getPage;
	Helpers.datepicker = datepicker;
	Helpers.phoneMask = phoneMask;
	Helpers.dateMask = dateMask;
	Helpers.timeMask = timeMask;
	Helpers.numberMask = numberMask;
	Helpers.cnpjMask = cnpjMask;
	Helpers.cpfMask = cpfMask;
	Helpers.cpfTest = cpfTest;
	Helpers.cpfcnpjMask = cpfcnpjMask;
	Helpers.cepMask = cepMask;
	Helpers.loadCep = loadCep;
	Helpers.loadShippingCep = loadShippingCep;
	Helpers.moneyMask = moneyMask;
	Helpers.rgMask = rgMask;
	Helpers.heigthMask = heigthMask;
	// Helpers.weightMask		 	= weightMask;
	Helpers.searchTable = searchTable;
	Helpers.searchDropdown = searchDropdown;
	Helpers.username = username;
	Helpers.codeMask = codeMask;
	Helpers.cardnumberMask = cardnumberMask;
	Helpers.numberMonthMask = numberMonthMask;
	Helpers.numberYearMask = numberYearMask;
	Helpers.cvvMask = cvvMask;
	Helpers.cardValidateMask = cardValidateMask;
	Helpers.isValidEmailAddress = isValidEmailAddress;
	Helpers.coinMask = coinMask;
	Helpers.ftMask = ftMask;
	Helpers.mMask = mMask;
	Helpers.kgMask = kgMask;
	Helpers.gMask = gMask;
	Helpers.ptsMask = ptsMask;
	Helpers.cmMask = cmMask;
	Helpers.cmIntMask = cmIntMask;
	Helpers.temperatureMask = temperatureMask;
	Helpers.landlineMask = landlineMask;
	Helpers.checkNumberCard = checkNumberCard;

})($, Helpers);