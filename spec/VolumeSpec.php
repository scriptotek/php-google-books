<?php

namespace spec\Scriptotek\GoogleBooks;

use Mockery as m;
use Scriptotek\GoogleBooks\GoogleBooks;
use Scriptotek\GoogleBooks\Volume;
use PhpSpec\ObjectBehavior;

class VolumeSpec extends ObjectBehavior
{
    function init($volume = 'volume_from_list.json', $volumeFull = 'volume.json')
    {
        $books = m::mock(GoogleBooks::class);

        if (!is_null($volumeFull)) {
            $json = json_decode(file_get_contents(__DIR__ . '/dummy/' . $volumeFull));
            $books->shouldReceive('getItem')->andReturn($json);
        }

        $this->beConstructedWith(
            $books,
            json_decode(file_get_contents(__DIR__ . '/dummy/' . $volume))
        );
    }

    function it_is_initializable()
    {
        $this->init();
        $this->shouldHaveType(Volume::class);
    }

    public function it_should_provide_cover_and_remove_page_curl()
    {
        $this->init();
        $this->getCover('small')->shouldBe('http://books.google.com/books/content?id=kPbzR4LDkAkC&printsec=frontcover&img=1&zoom=2&edge=none&imgtk=AFLRE72IdCBUcw24r5hqyGPQwmrHJk6OLVOKR9XNrn8mH4RIQIEvhFEcnKrcOPhnjEM8g4PeCDoUaKB6HAVP_FTO_3HkgHqEujYmHK1TrvSxdtPr8GkWPSfTyEINR4FRDvSPi158C0-C&source=gbs_api');
    }

    public function it_should_provide_extra_large_cover_by_default()
    {
        $this->init();
        $this->getCover()->shouldBe('http://books.google.com/books/content?id=kPbzR4LDkAkC&printsec=frontcover&img=1&zoom=6&edge=none&imgtk=AFLRE73RQFMDXXY6BW11l7YiQeaWsOZhncZCFnaZQjGFJJsyHKOTXbOvzxz9KONWID-PzifHtofRKqxtAO-9EioQeR9dD2FIGeUzsHUw1ivMBSvhy32djTkg6uu7jfGETwYA42O2KpoL&source=gbs_api');
    }

    public function it_should_provide_other_cover_if_extra_large_cover_is_not_available()
    {
        $this->init('volume_from_list.json', 'volume_missing_some_imagelinks.json');
        $this->getCover()->shouldBe('http://books.google.com/books/content?id=kPbzR4LDkAkC&printsec=frontcover&img=1&zoom=3&edge=none&imgtk=AFLRE736QO2TE3guH62ldy2a_1dFbOX8hPvakUOPdbtiiq4K1X1hNINzESxqqM3pfJKKIeuNpTutASxIysoBQlyDIh-I1Bm2_Ct0ICL97OD4_J50wPhVuUJrYhHR8kXgLylEslwukhnv&source=gbs_api');
    }

    public function it_should_handle_responses_without_covers_gracefully()
    {
        $this->init('volumes_no_imagelinks.json');
        $this->getCover()->shouldBe(null);
    }

    public function it_should_have_title()
    {
        $this->init();
        $this->title->shouldBe('Thermodynamics of Solar Energy Conversion');
    }

    public function it_should_have_description()
    {
        $this->init();
        $this->description->shouldBe('The physical framework used to describe the various conversions is endoreversible thermodynamics, a subset of irreversible thermodynamics.');
    }

    public function it_should_be_string_serializable()
    {
        $this->init();
        $this->__toString()->shouldStartWith('{');
    }
}
