<div class="foot-beam-full-color">
    <div
            class="dsh-container-newsletter relative-cont"
            ng-controller="SubscribeNewsletterController"
            ng-model="loadingclass"
            ng-init="loadingclass='';initData()"
    >

        <div class="newsletter-info-txt">
            {{ __('translations.newsletter_view_info') }}
        </div>


        <form name="subscribe" ng-submit="subscribeAdd()">

            <div class="gird-row transparent-gird-row gird-newsletter">



                <div class="mask-loading" ng-class="loadingclass">

                </div>

                <div class="input-left-gird">
                    <label class="hidden" for="newsletter-mail">Wpisz email</label>
                    <input
                            type="text"
                            name="memberemail"
                            class="newsletter-input"
                            id="newsletter-mail"
                            ng-model="memberemail"
                            placeholder="wpisz swój email"
                            ng-pattern="emailregex"
                            ng-class="emailvalid"
                            ng-change="(subscribe.memberemail.$valid)?(emailvalid=''):(emailvalid='error')"
                            >
                    <div class="image-loading-newsletter" ng-class="loadingclass">
                        <img src="{{ asset('images/loading-eclipse.svg') }}" title="obrazek ładownia widoczny przy zapisie na newsletter" alt="obrazek ładownia widoczny przy zapisie na newsletter">
                    </div>

                    <div class="cloud-raport-newsletter" ng-if="show_raport_cloud">
                        [[ raporttext ]]
                    </div>

                </div>

                <div class="button-right-gird">
                    <button type="submit" class="btn-newsletter">WYŚLIJ</button>
                </div>

            </div>


        </form>


    </div>
</div>