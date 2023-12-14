<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Application\Store\AddCartItemUserCase;
use AppCore\Application\Store\AddProductFavoriteUseCase;
use AppCore\Application\Store\DeleteCartItemUseCase;
use AppCore\Application\Store\DeleteProductFavoriteUseCase;
use AppCore\Application\Store\ForgotCustomerPasswordUseCase;
use AppCore\Application\Store\GetCartUseCase;
use AppCore\Application\Store\GetProductFavoriteUseCase;
use AppCore\Application\Store\GetProductsUseCase;
use AppCore\Application\Store\JoinCustomerUseCase;
use AppCore\Application\Store\ResetCustomerPasswordUseCase;
use AppCore\Application\Store\VerifyJoinCustomerUseCase;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\Customer\CustomerRepositoryInterface;
use AppCore\Domain\CustomerFavoriteProduct\CustomerFavoriteProductRepositoryInterface;
use AppCore\Domain\CustomerStore\CustomerStoreRepositoryInterface;
use AppCore\Domain\Encrypter\EncrypterInterface;
use AppCore\Domain\Hasher\PasswordHasher;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Language\LanguageInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\EmailConfig;
use AppCore\Domain\Mail\EmailConfigInterface;
use AppCore\Domain\Mail\TemplateRenderer;
use AppCore\Domain\Mail\TemplateRendererInterface;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\Product\ProductRepositoryInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Domain\Shop\ShopRepositoryInterface;
use AppCore\Domain\ShopNotificationRecipient\ShopNotificationRecipientRepositoryInterface;
use AppCore\Domain\StaffMember\StaffMemberRepositoryInterface;
use AppCore\Domain\Stock\StockRepositoryInterface;
use AppCore\Domain\Store\StoreRepositoryInterface;
use AppCore\Domain\StoreFeePaymentIntent\StoreFeePaymentIntentRepositoryInterface;
use AppCore\Domain\StoreUsage\StoreUsageRepositoryInterface;
use AppCore\Domain\Tax\TaxRepositoryInterface;
use AppCore\Domain\Taxonomy\TaxonomyRepositoryInterface;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use AppCore\Infrastructure\Persistence\AdminRepository;
use AppCore\Infrastructure\Persistence\CustomerFavoriteProductRepository;
use AppCore\Infrastructure\Persistence\CustomerRepository;
use AppCore\Infrastructure\Persistence\CustomerStoreRepository;
use AppCore\Infrastructure\Persistence\ProductRepository;
use AppCore\Infrastructure\Persistence\ShopNotificationRecipientRepository;
use AppCore\Infrastructure\Persistence\ShopRepository;
use AppCore\Infrastructure\Persistence\StaffMemberRepository;
use AppCore\Infrastructure\Persistence\StockRepository;
use AppCore\Infrastructure\Persistence\StoreFeePaymentIntentRepository;
use AppCore\Infrastructure\Persistence\StoreRepository;
use AppCore\Infrastructure\Persistence\StoreUsageRepository;
use AppCore\Infrastructure\Persistence\TaxonomyRepository;
use AppCore\Infrastructure\Persistence\TaxRepository;
use AppCore\Infrastructure\Shared\AdminLogger;
use AppCore\Infrastructure\Shared\CustomerLogger;
use AppCore\Infrastructure\Shared\Encrypter;
use AppCore\Infrastructure\Shared\SalonLogger;
use AppCore\Infrastructure\Shared\SecureRandom;
use AppCore\Infrastructure\Shared\SmtpMail;
use AppCore\Infrastructure\Shared\UrlSignatureEncrypter;
use AppCore\Qualifier\AdminAddress;
use AppCore\Qualifier\AdminLogger as QualifierAdminLogger;
use AppCore\Qualifier\EmailDir;
use AppCore\Qualifier\EncryptPass;
use AppCore\Qualifier\HashSalt;
use AppCore\Qualifier\LangDir;
use AppCore\Qualifier\NotifierAddress;
use AppCore\Qualifier\SalonLogger as QualifierSalonLogger;
use AppCore\Qualifier\SmptTransport;
use AppCore\Qualifier\StoreLogger as QualifierCustomerLogger;
use AppCore\Qualifier\WebsiteUrl;
use MyVendor\MyProject\Form\UploadFilesInterface;
use MyVendor\MyProject\Provider\LanguageProvider;
use MyVendor\MyProject\Provider\PhpMailerProvider;
use MyVendor\MyProject\Provider\ServerRequestProvider;
use MyVendor\MyProject\Provider\UploadedFilesProvider;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Http\Message\ServerRequestInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

use function getenv;
use function parse_str;
use function random_bytes;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
final class BaseModule extends AbstractModule
{
    public function __construct(
        private readonly string $langDir,
        private readonly string $emailDir,
        AbstractModule|null $module = null,
    ) {
        parent::__construct($module);
    }

    protected function configure(): void
    {
        $this->core();
        $this->language();
        $this->logger();
        $this->url();
        $this->email();
        $this->persistence();
        $this->usecase();
    }

    private function core(): void
    {
        $this->bind()->annotatedWith(EncryptPass::class)->toInstance((string) getenv('ENCRYPT_PASS'));
        $this->bind(EncrypterInterface::class)->to(Encrypter::class)->in(Scope::SINGLETON);

        $this->bind()->annotatedWith(HashSalt::class)->toInstance(random_bytes(32));
        $this->bind(SecureRandomInterface::class)->to(SecureRandom::class)->in(Scope::SINGLETON);

        $this->bind(PasswordHasherInterface::class)->to(PasswordHasher::class)->in(Scope::SINGLETON);

        $this->bind(ServerRequestInterface::class)->toProvider(ServerRequestProvider::class);

        $this->bind(UploadFilesInterface::class)->toProvider(UploadedFilesProvider::class);

        $this->bind(UrlSignatureEncrypterInterface::class)->to(UrlSignatureEncrypter::class)->in(Scope::SINGLETON);
    }

    public function language(): void
    {
        $this->bind()->annotatedWith(LangDir::class)->toInstance($this->langDir);
        $this->bind(LanguageInterface::class)->toProvider(LanguageProvider::class)->in(Scope::SINGLETON);
    }

    private function logger(): void
    {
        //@formatter:off
        $this->bind(LoggerInterface::class)->annotatedWith(QualifierAdminLogger::class)->to(AdminLogger::class)->in(Scope::SINGLETON);
        $this->bind(LoggerInterface::class)->annotatedWith(QualifierCustomerLogger::class)->to(CustomerLogger::class)->in(Scope::SINGLETON);
        $this->bind(LoggerInterface::class)->annotatedWith(QualifierSalonLogger::class)->to(SalonLogger::class)->in(Scope::SINGLETON);
        //@formatter:on
    }

    private function url(): void
    {
        $this->bind()->annotatedWith(WebsiteUrl::class)->toInstance(getenv('WEBSITE_URL'));
    }

    private function email(): void
    {
        $this->bind()->annotatedWith('admin_email_address')->toInstance(getenv('ADMIN_EMAIL_ADDRESS'));
        $this->bind()->annotatedWith('admin_email_name')->toInstance(getenv('ADMIN_EMAIL_NAME'));
        $this->bind(AddressInterface::class)->annotatedWith(AdminAddress::class)
            ->toConstructor(Address::class, [
                'address' => 'admin_email_address',
                'name' => 'admin_email_name',
            ])->in(Scope::SINGLETON);

        $this->bind()->annotatedWith('notifier_email_address')->toInstance(getenv('NOTIFIER_EMAIL_ADDRESS'));
        $this->bind()->annotatedWith('notifier_email_name')->toInstance(getenv('NOTIFIER_EMAIL_NAME'));
        $this->bind(AddressInterface::class)->annotatedWith(NotifierAddress::class)
            ->toConstructor(Address::class, [
                'address' => 'notifier_email_address',
                'name' => 'notifier_email_name',
            ])->in(Scope::SINGLETON);

        $this->bind()->annotatedWith('smtp_hostname')->toInstance(getenv('SMTP_HOST'));
        $this->bind()->annotatedWith('smtp_port')->toInstance((int) getenv('SMTP_PORT'));
        $this->bind()->annotatedWith('smtp_username')->toInstance(getenv('SMTP_USER'));
        $this->bind()->annotatedWith('smtp_password')->toInstance(getenv('SMTP_PASS'));
        $smtpOptions = [];
        parse_str((string) getenv('SMTP_OPTION'), $smtpOptions);
        $this->bind()->annotatedWith('smtp_options')->toInstance($smtpOptions);
        $this->bind(EmailConfigInterface::class)
            ->toConstructor(EmailConfig::class, [
                'hostname' => 'smtp_hostname',
                'port' => 'smtp_port',
                'username' => 'smtp_username',
                'password' => 'smtp_password',
                'options' => 'smtp_options',
            ])->in(Scope::SINGLETON);
        $this->bind(PHPMailer::class)->toProvider(PhpMailerProvider::class)->in(Scope::SINGLETON);

        $this->bind()->annotatedWith(EmailDir::class)->toInstance($this->emailDir);
        $this->bind(TransportInterface::class)->annotatedWith(SmptTransport::class)
            ->to(SmtpMail::class)->in(Scope::SINGLETON);
//        $this->bind(TransportInterface::class)->annotatedWith('queue')->to(QueueMail::class)->in(Scope::SINGLETON);

        $this->bind(TemplateRendererInterface::class)->to(TemplateRenderer::class)->in(Scope::SINGLETON);
    }

    private function persistence(): void
    {
        //@formatter:off
        $this->bind(AdminRepositoryInterface::class)->to(AdminRepository::class)->in(Scope::SINGLETON);
        $this->bind(CustomerFavoriteProductRepositoryInterface::class)->to(CustomerFavoriteProductRepository::class)->in(Scope::SINGLETON);
        $this->bind(CustomerRepositoryInterface::class)->to(CustomerRepository::class)->in(Scope::SINGLETON);
        $this->bind(CustomerStoreRepositoryInterface::class)->to(CustomerStoreRepository::class)->in(Scope::SINGLETON);
        $this->bind(ProductRepositoryInterface::class)->to(ProductRepository::class)->in(Scope::SINGLETON);
        $this->bind(ShopRepositoryInterface::class)->to(ShopRepository::class)->in(Scope::SINGLETON);
        $this->bind(ShopNotificationRecipientRepositoryInterface::class)->to(ShopNotificationRecipientRepository::class)->in(Scope::SINGLETON);
        $this->bind(StaffMemberRepositoryInterface::class)->to(StaffMemberRepository::class)->in(Scope::SINGLETON);
        $this->bind(StockRepositoryInterface::class)->to(StockRepository::class)->in(Scope::SINGLETON);
        $this->bind(StoreFeePaymentIntentRepositoryInterface::class)->to(StoreFeePaymentIntentRepository::class)->in(Scope::SINGLETON);
        $this->bind(StoreRepositoryInterface::class)->to(StoreRepository::class)->in(Scope::SINGLETON);
        $this->bind(StoreUsageRepositoryInterface::class)->to(StoreUsageRepository::class)->in(Scope::SINGLETON);
        $this->bind(TaxRepositoryInterface::class)->to(TaxRepository::class)->in(Scope::SINGLETON);
        $this->bind(TaxonomyRepositoryInterface::class)->to(TaxonomyRepository::class)->in(Scope::SINGLETON);
        //@formatter:on
    }

    public function usecase(): void
    {
        $this->bind(AddCartItemUserCase::class)->in(Scope::SINGLETON);
        $this->bind(AddProductFavoriteUseCase::class)->in(Scope::SINGLETON);
        $this->bind(DeleteCartItemUseCase::class)->in(Scope::SINGLETON);
        $this->bind(DeleteProductFavoriteUseCase::class)->in(Scope::SINGLETON);
        $this->bind(ForgotCustomerPasswordUseCase::class)->in(Scope::SINGLETON);
        $this->bind(GetCartUseCase::class)->in(Scope::SINGLETON);
        $this->bind(GetProductFavoriteUseCase::class)->in(Scope::SINGLETON);
        $this->bind(GetProductsUseCase::class)->in(Scope::SINGLETON);
        $this->bind(JoinCustomerUseCase::class)->in(Scope::SINGLETON);
        $this->bind(ResetCustomerPasswordUseCase::class)->in(Scope::SINGLETON);
        $this->bind(VerifyJoinCustomerUseCase::class)->in(Scope::SINGLETON);
    }
}
