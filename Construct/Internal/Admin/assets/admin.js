$(document).ready(function () {
    "use strict";
    ipAdmin.init();
});

var ipAdmin = new function () {
    "use strict";
    var $adminMenu;
    var $adminMenuContainer;
    var $container;
    var $currentItem;
    var $menubar;

    this.init = function () {
        $(document.body).prepend($(ipAdminNavbar));
        $container = $('.ipsAdminNavbarContainer'); // the most top element physically creates a space
        $menubar = $('.ipsAdminNavbar'); // Administration Panel that stays always visible
        $currentItem = $('.ipsItemCurrent');

        $adminMenu = $('.ipsAdminMenuBlock');
        $adminMenuContainer = $('.ipsAdminMenuBlockContainer');

       $('body').on('click','.ipsAdminMenu', function (e) {
            showAdminMenu();
             $('.ipsAdminMenu').addClass('open');
        });

        $('.ipsAdminLogout').on('click', function (e) {
            e.preventDefault();
            logout();
        });

         $('body').on('click','.ipsAdminMenu.open', function (e) {
            
            hideAdminMenu();
             $('.ipsAdminMenu').removeClass('open');
        });



        fixLayout();
       onResize();

        if (!ip.isManagementState) {
            $('.ipsContentPublish').on('click', function (e) {
                save(true)
            });
            $('.ipsContentSave').addClass('hidden');
        }

        $(window).bind('resize.ipAdmin', onResize);

        $(document).trigger('ipAdminPanelInit');
    };

    var showAdminMenu = function () {
           document.getElementById("offside").style.width = "250px";
           document.getElementById("toggler").style.marginLeft = "250px";
 //$currentItem.addClass('hidden');
     //   $adminMenu.removeClass('invisible');
    };

    var hideAdminMenu = function () {
            document.getElementById("offside").style.width = "0px";
           document.getElementById("toggler").style.marginLeft = "0px";
   };

    var fixLayout = function () {
        $container.height($menubar.outerHeight()); // setting the height to container
    };

    var logout = function () {
        $.ajax({
            type: 'GET',
            url: ip.baseUrl,
            data: {sa: 'Admin.logout'},
            complete: function () {
                if (window.location.toString().indexOf('?aa') !== -1) {
                    window.location = ip.baseUrl; //we are in admin. Redirect to the homepage
                } else {
                    window.location.reload(true); //we are on the actual page. Let's show that page
                }
            },
           dataType: 'json'
        });
    };


    //TODO this function is duplicated in jquery.ip.contentManagement
    var save = function (publish) {
        var $this = $(this);

        var postData = Object();
        postData.aa = 'Content.savePage';
        postData.securityToken = ip.securityToken;
        postData.revisionId = ip.revisionId;
        if (publish) {
            postData.publish = 1;
        } else {
            postData.publish = 0;
        }

        $.ajax({
            type: 'POST',
            url: ip.baseUrl,
            data: postData,
            context: $this,
            success: _savePageResponse,
            dataType: 'json'
        });
    };

    //TODO this function is duplicated in jquery.ip.contentManagement
    var _savePageResponse = function (response) {
        if (response.status == 'success') {
            window.location.href = response.newRevisionUrl;
        }
    };

    var onResize = function () {
        // Admin menu height
        var containerHeight = $(window).height() - $menubar.outerHeight();

        if ($adminMenu.height() > containerHeight) {
            $adminMenuContainer.height(containerHeight);
        }
        

        // elements with 'ipsAdminAutoHeight' CSS class
        var $container = $(window);
        var $elements = $('.ipsAdminAutoHeight');
        var containerHeight = parseInt($container.outerHeight());
        var menubarHeight = parseInt($('.ipsAdminNavbarContainer').outerHeight());
        if (menubarHeight > $adminMenu.height()) {
            containerHeight -= menubarHeight; // leaving place for menubar
        }
        $elements.css('min-height', containerHeight);
    }

};
