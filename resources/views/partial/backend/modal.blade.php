<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="exampleModalLabel">مستعد لمغادرة النظام?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div dir="rtl" class="modal-body text-center">تذكر بأن تأخد نسخة احطياطية من البيانات من وقت إلى آخر .</div>
            <div class="modal-footer bg-light">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">@lang('site.cancel')</button>
                <a href="javascript:void(0);" class="btn btn-primary text-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">@lang('site.logout')</a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
